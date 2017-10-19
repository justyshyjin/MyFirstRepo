<?php

/**
 * Upload Repository
 *
 * To manage the functionalities related to the file uploads.
 * @vendor Contus
 *
 * @package Base
 * @name UploadRepository
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Base\Repositories;

use Contus\Base\Contracts\IUploadRepository;
use Contus\Base\Repository as BaseRepository;
use Illuminate\Contracts\Validation\Factory;
use Symfony\Component\HttpFoundation\File\File;
use Contus\Base\Contracts\AttachableModel as AttachableModel;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Exception;
use Contus\Base\Model;
use Contus\Base\Repositories\Config;
use Contus\Base\Repositories\Fileupload;
use Contus\Base\Repositories\Requestflow;
use Illuminate\Support\Facades\File as Makefile;
use Aws\S3\S3Client;
use Aws\ElasticTranscoder\ElasticTranscoderClient;
use Contus\Video\Models\Video;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Video\Models\TranscodedVideo;
use Contus\Video\Models\VideoPreset;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UploadRepository extends BaseUploadRepository implements IUploadRepository {
    /**
     * Class property to hold the request param key which is holding the uploaded file
     *
     * @var string
     */
    protected $requestParamKey = 'image';
    /**
     * Class property to hold the request param key which is flag tell file removed or not after uploaded
     *
     * @var string
     */
    public $removedFlagParamKey = 'removed';
    /**
     * Class property to hold the request param key which is flag tell file removed or not after uploaded
     *
     * @var string
     */
    protected $tempImageParamKey = 'temp';
    /**
     * Class property to hold the config related to setting by model
     *
     * @var object
     */
    protected $config = null;
    /**
     * Class property to hold the storge path
     * independend of the type of upload
     *
     * @var object
     */
    protected $path = null;
    /**
     * Class property to hold the temp storge path
     * independend of the type of upload
     *
     * @var object
     */
    protected $tempPath = null;
    /**
     * Class property to hold the uploaded file
     * In Case if it is a multiple upload it will be a array
     * else it a uploaded file object
     *
     * @var mixed (array)
     */
    protected $uploadedFiles = [ ];
    /**
     * Class property to hold the model property should be set on each file model instance
     *
     * @var array
     */
    protected $modelProperties = [ ];
    /**
     * Class constants for holding various
     * Model identifier using upload repo
     *
     * @var const
     */
    const MODEL_IDENTIFIER_THUMBNAIL = 'thumbnail';
    const MODEL_IDENTIFIER_ADMINUSER = null;
    const MODEL_IDENTIFIER_PROFILE = 'profile';
    const MODEL_IDENTIFIER_CATEGORY_IMAGE = 'category_image';
    const MODEL_IDENTIFIER_STATICBANNER = 'static_banner';
    const MODEL_IDENTIFIER_SUBTITLE = 'subtitle';
    const MODEL_IDENTIFIER_POSTER = 'posters';
    const MODEL_IDENTIFIER_CAST_IMAGE = 'cast_images';
    /**
     * Class property to hold the allowed model identifiers
     *
     * @var array
     */
    protected $allowedModelIdentifier = [ self::MODEL_IDENTIFIER_THUMBNAIL,self::MODEL_IDENTIFIER_PROFILE,self::MODEL_IDENTIFIER_CATEGORY_IMAGE,self::MODEL_IDENTIFIER_SUBTITLE,self::MODEL_IDENTIFIER_POSTER,self::MODEL_IDENTIFIER_CAST_IMAGE,self::MODEL_IDENTIFIER_STATICBANNER ];

    /**
     * Class property to hold the model identifier
     *
     * @var string
     */
    protected $modelIdentifier = null;
    /**
     * Class property to hold the various model
     *
     * @var Contus\Base\Contracts\AttachableModel
     */
    protected $model = null;
    /**
     * Class intializer
     */
    public function __construct() {
        parent::__construct ();
        $this->awsRepository = new AWSUploadRepository ( new TranscodedVideo (), new VideoPreset () );
        app ( Factory::class )->extend ( 'resolution', function () {
            $arguments = func_get_args ();

            if (count ( $arguments ) > 3) {
                /**
                 * we make files as array so we can check the resolution for every file uploaded
                 */
                $files = is_array ( $arguments [1] ) ? $arguments [1] : [ $arguments [1] ];

                if (($expectedResolution = array_shift ( $arguments [2] )) && strpos ( $expectedResolution, "x" ) !== false) {
                    list ( $expectedWidth, $expectedHeight ) = explode ( 'x', $expectedResolution );

                    /**
                     * we will validate each files using array filter
                     */
                    return count ( array_filter ( $files, function ($file) use ($expectedWidth, $expectedHeight) {
                        list ( $fileWidth, $fileHeight ) = getimagesize ( $file->getRealPath () );

                        return $expectedWidth <= $fileWidth && $expectedHeight <= $fileHeight;
                    } ) ) == count ( $files );
                }
            }

            return false;
        } );

        app ( Factory::class )->replacer ( 'resolution', function ($message) {
            return str_replace ( ':resolution', $this->config->image_resolution, ucfirst ( $message ) );
        } );
    }

    /**
     * Created the temp for saving images with the folder permission for image uploading
     * @return string[]|NULL[]
     */
    public function tempUploadImage($types = ""){
        // Just imitate that the file was stored.
        
        $config = new Config();
        $tempDir = public_path() . '/temp';  
        $config->setTempDir($tempDir);
        $destination =  public_path().'/contus/files/';
        if (!file_exists($tempDir)) {
            Makefile::makeDirectory ( $tempDir, 0777, true , true);
        }
        if (!file_exists($destination)) {
            Makefile::makeDirectory ( $destination, 0777, true , true);
        }
        $requests = new Requestflow();
        $file = new Fileupload($config, $requests);
        $response = [];
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (!$file->checkChunk()) {
                $response[] = "404";
            }
        } else {
            if ($file->validateChunk()) {
                $file->saveChunk();
            } else {
                // error, invalid chunk upload request, retry
                $response [] = '400';
            }
        }

        if ($file->validateFile() && $file->save($destination . $requests->getFileName())) {
            $response [] = $requests->getFileName();
        }
        if(!empty($types) && ($types == 'groups' || $types == 'playlists')){
            $mimeType = Makefile::mimeType($destination.'/'.$requests->getFileName());
            $this->resizeImage($mimeType,$destination,$requests->getFileName());
        } 
        $orgFilename = $this->generateFileName($requests->getFileName());  
        $imageUrl = $this->awsRepository->uploadFileToS3 ( $destination.$requests->getFileName() ,$orgFilename,'images');
        if($imageUrl){
            $imageUrl = explode("/",$imageUrl);
            $imageUrl = $imageUrl[count($imageUrl)-2].'/'.$imageUrl[count($imageUrl)-1];
        } 
        return $imageUrl;
    }
     /**
     * Upload the file to temporary path
     * and store the file information in the session
     *
     * @return array @vendor Contus
     * @package Base
     * @throws Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function tempUpload() {
        $uploadedFiles = [ ];
        try {
            foreach ( $this->uploadedFiles as $file ) {
                $fileName = $this->makeTemporaryFileName ( $file );
                $mime = $file->getMimeType();
                if($file->move ( $this->path, $fileName ) && Makefile::exists($this->path.'/'.$fileName)){
                    $this->resizeImage($mime,$this->path,$fileName);
                }
                $filePath = $this->awsRepository->uploadFileToS3 ( $this->path.'/'.$fileName,$fileName );
                $uploadedFiles [] = $filePath;
            }
        }
        catch ( Exception $e ) {
            $this->logger->error ( $e->getMessage () );
        }

        return $uploadedFiles;
    }
    public function resizeImage($mime,$path,$fileName){
        if(substr($mime, 0, 5) == 'image') {
            $imageDimention = getimagesize($path.'/'.$fileName);
            $img = Image::make($path.'/'.$fileName);
            if($imageDimention[0] >= 600 && $imageDimention[1] >= 600){
                $img->resize(intval(550, 300), null, function($constraint) {
                    $constraint->aspectRatio();
                });
            }
            $img->save($path.'/'.$fileName);
        }
    }
    /**
     * Upload the file to actual path from request
     * all the files are try to uploaded even if some file has exception
     * single file is send to the AttchableModel
     *
     * @vendor Contus
     *
     * @package Base
     * @return void
     *
     * @throws Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function singleUpload() {
        $file = $this->request->file ( $this->requestParamKey );

        try {
            $model = $this->model->getFileModel ()->setFile ( $file->move ( $this->path, $this->makeTemporaryFileName ( $file ) ) );
        }
        catch ( Exception $e ) {
            $this->logger->error ( $e->getMessage () );
        }

        $this->model->upload ( $model );
    }
    /**
     * Upload the file to actual path from request
     * all the files are try to uploaded even if some file has exception
     * multiple file is send to the AttchableModel
     *
     * @vendor Contus
     *
     * @package Base
     * @return void
     *
     * @throws Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function upload() {
        $fileModels = [ ];

        foreach ( $this->request->file ( $this->requestParamKey ) as $file ) {
            try {
                $fileModels [] = $this->model->getFileModel ()->setFileOptions ( [ 'name' => $file->getClientOriginalName () ] )->setFile ( $file->move ( $this->path, $this->makeTemporaryFileName ( $file ) ) );
            }
            catch ( Exception $e ) {
                $this->logger->error ( $e->getMessage () );
            }
        }

        $this->model->upload ( $fileModels );
    }
    /**
     * make temporary file name for the upload file
     *
     * @vendor Contus
     *
     * @package Base
     * @param Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return array
     */
    protected function makeTemporaryFileName(UploadedFile $uploadedFile) {
        
        if (isset ( $this->config->is_file ) && ($this->config->is_file)) {   
            $filePathInfo =  pathinfo($uploadedFile->getClientOriginalName ());
            $names = $filePathInfo['filename'];
            $currentTime = Carbon::now()->timestamp;
            $names = str_replace(' ', '_', $names);
            $names = preg_replace('/[^A-Za-z0-9\-]/', '', $names).'-'.$currentTime;
            return $names . "." . pathinfo ( $uploadedFile->getClientOriginalName (), PATHINFO_EXTENSION );
        }
        return uniqid () . "." . ($uploadedFile->guessExtension () ?: pathinfo ( $uploadedFile->getClientOriginalName (), PATHINFO_EXTENSION ));
    }
    /**
     * set uploaded file from the request
     * and make sure the uploadedFile class property has array of files
     *
     * @vendor Contus
     *
     * @package Base
     * @return UploadRepository
     */
    protected function setUploadedFilesFromRequest() {
        $uploadedFile = $this->request->hasFile ( $this->requestParamKey ) ? $this->request->file ( $this->requestParamKey ) : [ ];

        $this->uploadedFiles = is_array ( $uploadedFile ) ? $uploadedFile : [ $uploadedFile ];

        return $this;
    }
    /**
     * update the properties set earlier to the file models
     *
     * @param \Contus\Base\Model $model
     * @return UploadRepository
     */
    protected function updateModelProperties(Model $model) {
        foreach ( $this->modelProperties as $modelProperty => $modelValue ) {
            $model->{$modelProperty} = $modelValue;
        }

        return $model;
    }
    /**
     * Complete the upload by move the file from
     * temporary directory to the actual by model
     *
     * @vendor Contus
     *
     * @package Base
     * @return void
     */
    public function completeUpload() {
        $fileModels = [ ];

        foreach ( $this->getFilesInformation () as $fileInfo ) {
            $filePath = $this->getTempPath () . $fileInfo [$this->tempImageParamKey];

            if (! file_exists ( $filePath )) {
                $this->throwJsonResponse ( false, 403, trans ( 'base::upload.error.invalid' ) );
            }

            if ($this->isRemovedFile ( $fileInfo )) {
                if (file_exists ( $filePath )) {
                    unlink ( $filePath );
                }
                continue;
            }

            try {
                $model = $this->updateModelProperties ( $this->model->getFileModel () );

                if (method_exists ( $model, "setFileOptions" )) {
                    $model->setFileOptions ( $fileInfo );
                }

                $fileModels [] = $model->setFile ( (new File ( $filePath, true ))->move ( $this->path ), $this->config );
            }
            catch ( FileNotFoundException $e ) {
                $this->logger->error ( $e->getMessage () );
            }
        }

        $this->model->upload ( $fileModels );
    }
    /**
     * Complete the upload by move the file from
     * temporary directory to the actual by model
     *
     * @vendor Contus
     *
     * @package Base
     * @return void
     */
    public function completeSingleUpload() {
        $fileName = $this->request->input ( $this->requestParamKey );
        $fileModel = NULL;

        $filePath = $this->getTempPath () . $fileName;

        if (! file_exists ( $filePath )) {
            $this->throwJsonResponse ( false, 403, trans ( 'base::upload.error.invalid' ) );
        }

        try {
            $fileModel = $this->model->getFileModel ()->setFile ( (new File ( $filePath, true ))->move ( $this->path ), $this->config );

            $this->model->upload ( $fileModel );
        }
        catch ( FileNotFoundException $e ) {
            p ( $e->getMessage () );
            $this->logger->error ( $e->getMessage () );
        }
    }
    /**
     * Handle the file uploads for the user
     * if file upload has happened in ajax it in temporary path
     * else the handle file uploaded in the latest request
     *
     * @vendor Contus
     *
     * @package Base
     * @param Contus\Base\Contracts\AttachableModel $model
     * @return mixed
     */
    public function handleUpload(AttachableModel $model) {
        $this->model = $model;

        if ($this->hasTemporaryUpload ()) {
            $this->setStoragePath ();

            if (is_array ( $this->request->input ( $this->requestParamKey ) )) {
                $this->completeUpload ();
            } else {
                $this->completeSingleUpload ();
            }
        } else {
            $this->setStoragePath ();

            if (is_array ( $this->request->file ( $this->requestParamKey ) )) {
                $this->upload ();
            } else {
                $this->singleUpload ();
            }
        }
    }
    /**
     * Check the file already uploaded to temporary path through AJAX by model identifier
     * and check file is not uploaded in the current request
     *
     * @vendor Contus
     *
     * @package Base
     * @return boolean
     */
    public function hasTemporaryUpload() {
        return $this->request->has ( $this->requestParamKey ) && ! $this->request->hasfile ( $this->requestParamKey );
    }
    /**
     * get the files information from request
     * for temporary upload
     * only the file information request param key contain tempImageParamKey will be considered for upload
     *
     * @vendor Contus
     *
     * @package Base
     * @return array
     */
    public function getFilesInformation() {
        return array_filter ( $this->hasTemporaryUpload () ? $this->request->input ( $this->requestParamKey ) : [ ], function ($file) {
            return isset ( $file [$this->tempImageParamKey] ) && ! empty ( $file [$this->tempImageParamKey] );
        } );
    }
    /**
     * Define file rule for repository
     * rule is defined only if there is not file in temporary path
     * and file is uploaded
     * @vendor Contus
     *
     * @package Base
     * @param BaseRepository $repository
     * @return boolean
     */
    public function defineRepositoryFileRule(BaseRepository $repository) {
        if (! $this->hasTemporaryUpload () && $this->request->hasFile ( $this->requestParamKey )) {
            $repository->setRule ( $this->requestParamKey, $this->defineRule ()->getRule ( $this->requestParamKey ) );
        }
    }
    public function generateFileName($filename){
        $pathinfo =  pathinfo($filename);
        $extension = $pathinfo['extension'];
        $newname = $pathinfo['filename'];
        $current_time = Carbon::now()->timestamp;
        $newname = str_replace(' ', '_', $newname);
        $newname = preg_replace('/[^A-Za-z0-9\-]/', '', $newname).'-'.$current_time;
        return $newname.'.'.$extension;
    }
}
