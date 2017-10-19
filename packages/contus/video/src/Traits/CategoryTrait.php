<?php

/**
 * CategoryTrait
 *
 * To manage the functionalities related to the Categories module from Categories Controller
 *
 * @vendor Contus
 *
 * @package Categories
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Traits;

use Contus\Video\Contracts\ICategoryRepository;
use Contus\Video\Models\Category;
use Contus\Base\Repository as BaseRepository;
use Contus\Base\Repositories\UploadRepository;
use Illuminate\Support\Facades\Hash;
use Contus\Base\Helpers\StringLiterals;
use Contus\Video\Models\Video;
use Contus\Video\Models\Comment;
use Contus\Video\Models\Collection;
use Contus\Customer\Models\MypreferencesVideo;
use Illuminate\Support\Facades\Cache;

trait CategoryTrait {
    /**
     * Function to get all categories.
     *
     * @return array All categories.
     */
    public function getChildCategoryEach($value) {
        $subcatvalue = array ();
        foreach ( $value ['child_category'] as $newvalue ) {
            if (count ( $newvalue ['child_category'] ) > 0) {
                foreach ( $newvalue ['child_category'] as $subcatnewvalue ) {
                    if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
                        $subcatvalue [$subcatnewvalue [$this->getKeySlugorId ()]] = $subcatnewvalue ['title'];
                    } else {
                        $subcatvalue [$subcatnewvalue ['id']] = $value ['title'] . ' > ' . $newvalue ['title'] . ' > ' . $subcatnewvalue ['title'];
                    }
                }
            }
        }
        return $subcatvalue;
    }

    /**
     * Function to get all categories.
     *
     * @return array All categories.
     */
    public function getAllCategoriesSlugs() {
        if ($this->request->has ( 'main_category' ) && ! empty ( $this->request->main_category )) {
            return $this->_category->where ( 'parent_id', 0 )->where ( $this->getKeySlugorId (), $this->request->main_category )->has ( 'child_category.child_category.videos' )->where ( 'is_active', 1 )->with ( [ 'child_category' => function ($query) {
                return $query->has ( 'child_category.videos' )->with ( [ 'child_category' => function ($query) {
                    return $query->has ( 'videos' )->with ( 'videosCount' )->orderBy ( 'id', 'desc' );
                } ] )->orderBy ( 'is_leaf_category', 'asc' );
            } ] )->first ();
        }
        return $this->_category->where ( 'parent_id', 0 )->where ( 'is_active', 1 )->has ( 'child_category.child_category.videos' )->with ( [ 'child_category' => function ($query) {
            return $query->has ( 'child_category.videos' )->with ( [ 'child_category' => function ($query) {
                return $query->has ( 'videos' )->with ( 'videosCount' );
            } ] );
        } ] )->get ();
    }
    /**
     * Funtion to get related category video with complete information using slug
     *
     * @vendor Contus
     *
     * @package video
     * @return array
     */
    public function getRelatedVideoSlug($slug, $getCount = 10, $paginate = true) {
        $video = new Video ();
        $video = $video->whereCustomer ()->where ( $this->getKeySlugorId (), $slug )->first ()->categories ();
        if (! $video->get ()->toArray ()) {
            return [ ];
        }
        $result= '';
        if ($this->request->header ( 'x-request-type' ) == 'mobile') {
            $video = $video->first ()->videos ()->orderBy ( 'video_order', 'asc' );
            $video = $video->where ( 'videos.' . $this->getKeySlugorId (), '!=', $slug )->leftJoin ( 'favourite_videos as f1', function ($j) {
                $j->on ( 'videos.id', '=', 'f1.video_id' )->on ( 'f1.customer_id', '=', \DB::raw ( (auth ()->user ()) ? auth ()->user ()->id : 0 ) );
            } )->selectRaw ( 'videos.*,count(f1.video_id) as is_favourite' )->groupBy ( 'videos.id' )->with ( [ 'categories.parent_category.parent_category' ] )->where ( 'youtube_live', '==', 0 )->orderBy ( 'video_order', 'asc' );
            if ($paginate) {
                $video = $video->paginate ( $getCount );
            } else {
                $video = ($getCount) ? $video->take ( $getCount )->get () : $video->get ();
            }
            $result= ($paginate) ? $video->toArray () : $video;
        } else {
            $cat = $video->first ();
            if (Cache::has ( 'relatedCategoryList' . $cat->slug ) || $this->request->input ( 'page' ) > 1) {
                return Cache::rememberForever ( 'relatedCategoryList' . $cat->slug, function () use ($cat) {
                    if (Cache::has ( 'cache_keys_playlist' )) {
                        $previouscache = Cache::get ( 'cache_keys_playlist' );
                        if (! (strpos ( $previouscache, 'relatedCategoryList' . $cat->slug ) !== false)) {
                            Cache::put ( 'cache_keys_playlist', $previouscache . ',relatedCategoryList' . $cat->slug, 0 );
                        }
                    } else {
                        Cache::put ( 'cache_keys_playlist', 'relatedCategoryList' . $cat->slug, 0 );
                    }
                    $video = $cat->videos ()->orderBy ( 'video_order', 'asc' );
                    $video = $video->with ( [ 'categories' ] )->where ( 'youtube_live', '==', 0 )->orderBy ( 'video_order', 'asc' )->get ();
                    return [ 'data' => $video->toArray (),'next_page_url' => null,'total' => count ( $video ) ];
                } );
            } else {
                $video = $video->first ()->videos ()->orderBy ( 'video_order', 'asc' );
                $video = $video->with ( [ 'categories' ] )->where ( 'youtube_live', '==', 0 )->orderBy ( 'video_order', 'asc' );
                if ($paginate) {
                    $video = $video->paginate ( 5 );
                } else {
                    $video = ($getCount) ? $video->take ( $getCount )->get () : $video->get ();
                }
                $result= ($paginate) ? $video->toArray () : $video;
            }
        }
        return $result;
    }
    /**
     * Funtion to get parent category video with complete information using slug
     *
     * @vendor Contus
     *
     * @package video
     * @return array
     */
    public function getParentCategory($slug) {
        return $this->_category->where ( 'level', 0 )->where ( 'is_active', 1 )->where ( $this->getKeySlugorId (), $slug )->first ()->parent_category ()->first ()->parent_category ()->first ();
    }
    /**
     * Funtion to get parent category video with complete information using slug
     *
     * @package video
     * @return array
     */
    public function getChidCategory($slug) {
        return $this->_category->where ( 'level', 1 )->where ( 'is_active', 1 )->where ( $this->getKeySlugorId (), $slug )->has ( 'child_category.videos' )->with ( [ 'child_category' => function ($q) {
            return $q->where ( 'is_active', 1 )->has ( 'videos' )->orderBy ( 'is_leaf_category', 'desc' )->with ( [ 'videosCount' => function ($q) {
                return $q->selectRaw ( 'videos.selected_thumb' )->orderBy ( 'videos.id' );
            } ] );
        } ] )->first ();
    }

    /**
     * Funtion to get count of category video with complete information using slug
     *
     * @return array
     */
    public function getChidCategoryCount($slug) {
        return $this->_category->where ( 'level', 1 )->where ( 'is_active', 1 )->where ( $this->getKeySlugorId (), $slug )->with ( 'child_category' )->get ()->count ();
    }
    /**
     * Funtion to get category for navigation
     *
     * @vendor Contus
     *
     * @package video
     * @return array
     */
    public function getCategoiesNav($detail = false) {
        if ($detail) {
            $return = $this->_category->where ( 'level', 1 )->where ( 'is_active', 1 )->has ( 'child_category.videos' )->with ( [ 'parent_category','child_category' => function ($q) {
                return $q->where ( 'is_active', 1 )->has ( 'videos' )->orderBy ( 'is_leaf_category', 'desc' )->with ( 'videosCount' );
            } ] )->orderBy ( 'is_leaf_category', 'desc' )->get ();
        } else {
            $return = $this->_category->where ( 'level', 1 )->where ( 'is_active', 1 )->has ( 'child_category.videos' )->with ( 'parent_category' )->take ( 8 )->orderBy ( 'is_leaf_category', 'desc' )->get ();
            foreach ( $return as $k => $v ) {
                $return [$k] ['child_category'] = $v->child_category ()->has ( 'videos' )->with ( 'videosCount' )->orderBy ( 'is_leaf_category', 'desc' )->paginate ( 11 )->toArray ();
            }
        }
        return $return;
    }
    /**
     * Function to get all exams by categories
     *
     * @return object
     */
    public function getAllExamsByCategories() {
        $collection = new Collection ();
        if ($this->request->has ( 'exam_id' )) {
            $collection = $collection->where ( 'is_active', 1 )->where ( 'slug', $this->request->exam_id )->first ()->groups ()->has ( 'group_videos' )->with ( [ 'group_videos' => function ($query) {
                $query->selectRaw ( 'count(videos.id) as count' )->groupBy ( 'group_id' );
            } ] )->orderByRaw ( ' convert(`order`, decimal) desc ' )->get ();
        } else {
            $collection = $collection->where ( 'is_active', 1 )->has ( 'groups' )->orderBy ( 'order', 'desc' )->get ();
            
            if (count ( $collection )) {
                foreach($collection as $k=>$v){
                    $collection [$k] ['exams'] = $collection [$k]->groups ()->has ( 'group_videos' )->with ( [ 'group_videos' => function ($query) {
                        $query->selectRaw ( 'count(videos.id) as count' )->groupBy ( 'group_id' );
                    } ] )->orderByRaw ( 'convert(`order`, decimal) desc' )->get ();
                }
            }
        }
        return $collection;
    }

    /**
     * Funtion to get category types and exam types
     *
     * @return array
     */
    public function browsepreferenceListPlaylist() {
        $customer_preferences = MypreferencesVideo::where ( 'user_id', $this->authUser->id )->pluck( 'category_id' )->toArray ();
        $subcategory = $this->_category->where ( 'is_active', 1 )->where ( 'level', 1 )->whereNotIn ( 'id', $customer_preferences )->get ();
        $exams = Collection::where ( 'is_active', 1 )->whereNotIn ( 'id', $customer_preferences )->get ();
        if (isset ( $customer_preferences ) || (! empty ( $subcategory )) && $this->request->header ( 'x-request-type' ) == 'mobile') {
            return [ 'sub-categories' => $subcategory,'exam' => $exams ];
        }
    }
    /**
     * Funtion to get all category and exam types
     *
     * @return array
     */
    public function browsepreferenceListAll() {
        $subcategory = Category::where ( 'is_active', 1 )->where ( 'level', 1 )->has ( 'child_category.videos' )->with ( ['child_category_count' => function ($q) {
            return $q->where ( 'is_active', 1 )->has ( 'videos' );
        }] )->orderBy ( 'is_leaf_category', 'desc' )->get ();
        $exams = Collection::where ( 'is_active', 1 )->get ();
        return [ 'sub-categories' => $subcategory,'exam' => $exams ];
    }
}