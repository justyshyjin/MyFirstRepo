<?php

/**
 * Implements of Model
 *
 *
 * @name Model
 * @vendor Contus
 * @package Base
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Base;

use Illuminate\Database\Eloquent\Model as IlluminateModel;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exception\HttpResponseException;
use Contus\Video\Models\Deletefiles;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Video\Models\TranscodedVideo;
use Contus\Video\Models\VideoPreset;
use phpDocumentor\Reflection\Types\Object_;
use Illuminate\Support\Facades\Cache;

class Model extends IlluminateModel {

    protected $url = [ ];
    /**
     * Create image dynamically while saving
     */
    protected static function boot() {
        parent::boot ();
        static::saving ( function ($model) {
            $model->bootSaving ();
        } );
    }
    /**
     * Saving automation
     */
    public function bootSaving() {}
    /**
     * Set the hidden attributes for the model based on user.
     *
     * @param array $hidden
     * @return $this
     */
    public function setHiddenCustomer(array $hidden) {
        if (Config::get ( 'auth.providers.users.table' ) === 'customers') {
              if ((app ()->make ( 'request' )->header ( 'x-request-type' ) == 'mobile') && (($key = array_search ( 'id', $hidden )) !== false)) {
                unset ( $hidden [$key] );
            }
            $this->hidden = $hidden;
        }
        return $this;
    }

    /**
     * Set the visible attributes for the model based on user.
     *
     * @param array $visible
     * @return $this
     */
    public function setVisibleCustomer(array $visible) {
        if (Config::get ( 'auth.providers.users.table' ) === 'customers') {
            $this->visible = $visible;
        }
        return $this;
    }
    /**
     * Dynamic slug update
     *
     * @param string $createSlugFrom
     * @param string $thisSlug
     */
    public function setDynamicSlug($createSlugFrom, $thisSlug = 'slug') {
        $slug = str_slug ( $this->$createSlugFrom );
        if ($slug && empty ( $this->$thisSlug )) {
            $count = (! empty ( $this->getKey () )) ? $this->where ( $this->getKeyName (), '!=', $this->getKey () ) : '';
            $count = $this->where ( $thisSlug, 'like', '%' . $slug . '%' )->count ();
            $this->$thisSlug = ($count) ? $slug . '-' . $count : $slug;
        }
    }
    /**
     * Unlink the file already available in the database
     *
     * @param string $file
     * @return boolean
     */
    public function unlinkPreviousFile($file) {
        if (! $this->getKey ()) {
            return false;
        }
        $filePath = new $this ();
        $filePath = $filePath->where ( $this->getKeyName (), $this->getKey () )->select ( $file )->first ();
        if ($filePath[$file] != "" && $filePath->$file !== $this->$file) {
            $deletedFile = new Deletefiles ();
            $deletedFile->path = $filePath->$file;
            $deletedFile->save ();
        }
    }
    /**
     * Save the image url path in respective table
     *
     * @param unknown $image
     */
    public function saveImage($image, $validate = null) {
        $this->unlinkPreviousFile ( $image );
         if (app ()->make ( 'request' )->file ( $image )) {
            $aws = new AWSUploadRepository ( new TranscodedVideo (), new VideoPreset () );
            if ($validate === null) {
                throw new HttpResponseException ( new JsonResponse ( [ 'error' => true,'statusCode' => 422,'message' => ('Validator Not Available') ], 422 ) );
            }
            $validator = \Validator::make ( [ $image => $this->$image ], array ($image => $validate ) );
            if ($validator->fails ()) {
                $errors = $validator->messages ()->toArray ();
                throw new HttpResponseException ( new JsonResponse ( [ 'error' => true,'statusCode' => 422,'message' => ((app ()->make ( 'request' )->header ( 'x-request-type' ) == 'mobile') ? array_shift ( $errors ) [0] : $errors) ], 422 ) );
            }
            $destinationPath = public_path () . DIRECTORY_SEPARATOR . 'contus' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR;
            if (! \File::exists ( $destinationPath )) {
                \File::makeDirectory ( $destinationPath, 777, true, true );
            }
            $filename = $this->$image->getClientOriginalName ();
            $filename = pathinfo ( $filename, PATHINFO_FILENAME );
            $fullname = str_slug ( str_random ( 8 ) . $filename ) . '.' . $this->$image->getClientOriginalExtension ();
            $this->$image->move ( $destinationPath, $fullname );
            $this->$image = $fullname;
            $sourceLocation = public_path ( 'contus/files/' . $this->$image );

            $orgFilename = $this->generateFileName ( $this->$image );
            $filename = $aws->uploadFileToS3 ( $sourceLocation, $orgFilename, 'images' );
            if ($filename) {
                $imageUrl = explode ( "/", $filename );
                $imageUrl = $imageUrl [count ( $imageUrl ) - 2] . '/' . $imageUrl [count ( $imageUrl ) - 1];
            }
            $this->$image = ( string ) $imageUrl;
        }
    }

    /**
     * This function used to generate file name to save the image
     *
     * @param unknown $filename
     * @return string
     */
    public function generateFileName($filename) {
        $filePathInfo =  pathinfo($filename);
        $fileExt = $filePathInfo['extension'];
        $setName = $filePathInfo['filename'];
        $num = Carbon::now()->timestamp;
        $setName = str_replace(' ', '_', $setName);
        $setName = preg_replace('/[^A-Za-z0-9\-]/', '', $setName).'-'.$num;
        return $setName.'.'.$fileExt;
    }

    /**
     * Set Default condition for frontend customer
     *
     * @return object
     */
    public function whereCustomer() {
        if (Config::get ( 'auth.providers.users.table' ) === 'customers') {
            return $this->where ( 'is_active', '1' );
        }
    }
    /**
     * Function to get all the column names of the current model
     */
    public function getTableColumns() {
        return $this->getConnection ()->getSchemaBuilder ()->getColumnListing ( $this->getTable () );
    }
    /**
     * Set Url for images and videos
     *
     * {@inheritdoc}
     *
     * @see \Illuminate\Database\Eloquent\Model::getAttributes()
     */
    public function setRawAttributes(array $attributes, $sync = false) {
        $url = $this->url;
        foreach ( $url as $genurl ) {
            $prefix = 'https://s3.' . config ()->get ( 'settings.aws-settings.aws-general.aws_region' ) . '.amazonaws.com/' . config ()->get ( 'settings.aws-settings.aws-general.aws_s3_bucket' ).'/';
            if (isset ( $attributes [$genurl] ) && $attributes [$genurl] && substr($attributes [$genurl], 0, strlen($prefix)) == $prefix) {
                $attributes [$genurl] = substr($attributes [$genurl], strlen($prefix));
            }
            if (isset ( $attributes [$genurl] ) && $attributes [$genurl] && filter_var ( $attributes [$genurl], FILTER_VALIDATE_URL ) === false) {
                if(app( 'request' )->session ()->get ( 'updated_version')){
                    $attributes [$genurl] = config ()->get ( 'settings.aws-settings.aws-general.aws_s3_image_base_url' ) . '/' . $attributes [$genurl];
                }
                else{
                    $attributes [$genurl] = config ()->get ( 'settings.aws-settings.aws-general.aws_s3_image_base_url_mobile' ) . '/' . $attributes [$genurl];
                }
            }
        }
        return parent::setRawAttributes ( $attributes );
    }
    /**
     * Clear cache based on the key
     *
     * {@inheritDoc}
     * @see \Illuminate\Database\Eloquent\Model::clearCache()
     */
    public function clearCache($keys){
        if(count($keys)){
            for($i = 0;$i < count($keys);$i++){
                Cache::forget($keys[$i]);
            }
        }
    }
}
