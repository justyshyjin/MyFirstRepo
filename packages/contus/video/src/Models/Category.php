<?php

/**
 * Categories Models.
 *
 * @name Categories
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Base\Model;
use Contus\Base\Helpers\StringLiterals;
use Contus\Video\Models\VideoCategory;
use Contus\Base\Contracts\AttachableModel;
use Symfony\Component\HttpFoundation\File\File;
use Contus\Video\Models\Video;
use Illuminate\Support\Facades\Cache;

class Category extends Model implements AttachableModel {

    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Video
     * @var string
     */
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package Video
     * @var array
     */
    protected $fillable = [ 'title',StringLiterals::ISACTIVE,'parent_id','level' ];
    /**
     * The attribute will used to generate url
     *
     * @var array
     */
    protected $url = [ 'image_url' ];

    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'id','is_active','image_path','is_deletable','is_leaf_category','level','parent_id','updated_at','created_at','updator_id','creator_id','pivot' ] );
    }

    /**
     * funtion to automate operations while Saving
     */
    public function bootSaving() {
        $this->setDynamicSlug ( 'title', 'slug' );
        $keysArray = array('category_listing_page','dashboard_categories','dashboard_exams','dashboard_categorynave');
        $this->clearCache($keysArray);
        Cache::forget ( 'relatedCategoryList' . $this->slug );
    }

    /**
     * HasOne relationship for category.
     */
    public function parent_category() {
        $retunParentCategory = $this->belongsTo ( Category::class, 'parent_id', 'id' );
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            $retunParentCategory = $retunParentCategory->where ( 'categories.is_active', 1 );
        }
        return $retunParentCategory;
    }
    /**
     * HasOne relationship for category.
     */
    public function child_category() {
        $returnChildCategory = $this->hasMany ( Category::class, 'parent_id', 'id' );
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            $returnChildCategory = $returnChildCategory->where ( 'categories.is_active', 1 )->orderBy ( 'is_leaf_category', 'desc' );
        }
        return $returnChildCategory;
    }
    /**
     * HasMany relationship between categories and video_categories
     */
    public function videocategory() {
        return $this->hasMany ( VideoCategory::class );
    }
    /**
     * belongs to many relationship between video and video_categories
     */
    public function videos() {
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            return $this->belongsToMany ( Video::class, 'video_categories' )->where ( 'videos.is_active', '1' )->where ( 'job_status', 'Complete' )->where ( 'is_archived', 0 );
        }
        return $this->belongsToMany ( Video::class, 'video_categories' )->withPivot ( 'category_id', 'video_id' )->where ( 'is_archived', 0 );
    }
    /**
     * belongs to many relationship with count
     */
    public function videosCount() {
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            return $this->belongsToMany ( Video::class, 'video_categories' )->where ( 'videos.is_active', '1' )->where ( 'job_status', 'Complete' )->where ( 'is_archived', 0 )->selectRaw ( 'count(*) as count' )->groupBy ( 'category_id' );
        }
        return $this->belongsToMany ( Video::class, 'video_categories' )->withPivot ( 'category_id', 'video_id' )->where ( 'is_archived', 0 )->selectRaw ( 'count(*) as count' )->groupBy ( 'category_id' );
    }
    /**
     * Method used to filter the users based on the request.
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $status) {
        if ($status == 'active') {
            $query->where ( StringLiterals::ISACTIVE, 1 );
        } else if ($status == 'in-active') {
            $query->where ( StringLiterals::ISACTIVE, 0 );
        }
        return $query;
    }
    /**
     * Get File Information Model
     * the model related for holding the uploaded file information
     *
     * @vendor Contus
     *
     * @package Category
     * @return Contus\Video\Models\Category
     */
    public function getFileModel() {
        return $this;
    }
    /**
     * Set the file to Staplaer
     *
     * @param \Symfony\Component\HttpFoundation\File\File $file
     * @param string $storagePath
     * @return void
     */
    public function setFile(File $file, $config) {
        $this->image_url = url ( "$config->storage_path/" . $file->getFilename () );
        $this->image_path = $file->getPathname ();

        return $this;
    }
    /**
     * Store the file information to database
     * if attachment model is already has record will update
     *
     * @param Contus\Video\Models\Category $category
     * @return boolean
     */
    public function upload(Category $category) {
        return $category->save ();
    }
    /**
     * HasMany relationship between categories and playlist
     */
    public function playlists() {
        return $this->hasMany ( Playlist::class, 'category_id' );
    }
    /**
     * HasOne relationship for category.
     */
    public function child_category_count() {
        $returnChildCategory = $this->hasMany ( Category::class, 'parent_id', 'id' );
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            $returnChildCategory = $returnChildCategory->where ( 'categories.is_active', 1 );
        }
        return $returnChildCategory->selectRaw ( 'parent_id,count(*) as total_categories' )->groupBy ( 'categories.parent_id' );
    }
}
