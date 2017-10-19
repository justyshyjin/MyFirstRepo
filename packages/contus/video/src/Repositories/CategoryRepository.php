<?php

/**
 * Category Repository
 *
 * To manage the functionalities related to the Categories module from Categories Controller
 *
 * @name CategoriesRepository
 * @vendor Contus
 * @package Categories
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Repositories;

use Contus\Video\Contracts\ICategoryRepository;
use Contus\Video\Models\Category;
use Contus\Base\Repository as BaseRepository;
use Contus\Base\Repositories\UploadRepository;
use Illuminate\Support\Facades\Hash;
use Contus\Base\Helpers\StringLiterals;
use Contus\Video\Models\Video;
use Contus\Video\Traits\CategoryTrait as CategoryTrait;
use Illuminate\Support\Facades\DB;

class CategoryRepository extends BaseRepository implements ICategoryRepository {
    use CategoryTrait;
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_category;
    /**
     * Class property to hold the key which hold the group name requested
     *
     * @var string
     */
    protected $requestedCategories = 'q';
    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package Video
     * @param Contus\Video\Models\Categories $categories
     */
    public function __construct(Category $category, UploadRepository $uploadRepository) {
        parent::__construct ();
        $this->_category = $category;
        $this->uploadRepository = $uploadRepository;

        $this->setRules ( [ StringLiterals::TITLE => 'required' ] );
    }
    /**
     * Store a newly created categories.
     *
     * @param $id input
     * values
     *
     * @vendor Contus
     * @package Video
     * @return boolean
     */
    public function addOrUpdateCategory($id = null) {
        if (! empty ( $id )) {
            $category = $this->_category->find ( $id );
            $this->setRule ( StringLiterals::TITLE, 'required' );
        } else {
            $category = new Category ();
            $category->creator_id = $this->authUser->id;
        }

        $this->_validate ();

        $category->fill ( $this->request->except ( '_token' ) );
        $category->is_leaf_category = $this->request->is_leaf_category;
        if (empty ( $this->request->parent_id )) {
            $category->level = '0';
            $category->parent_id = 0;
        } else {
            $category->level = $this->getHieraechyCountLevel ( $this->request->parent_id );
            $category->parent_id = $this->request->parent_id;
        }
        $imageArray = [];
        if($this->request->image && $this->request->image != ""){
            $imageArray = explode("/",$this->request->image);
            $imageArray = $imageArray[count($imageArray)-1];
            $category->image_url = $imageArray;
            $category->image_path = $imageArray;
        }
        $category->preference_order = ((int)$this->request->preference_order)?$this->request->preference_order:null;
        $category->updator_id = $this->authUser->id;
        if ($category->save ()) {
            return true;
        }
    }
    /**
     * Function to get hierarchy level of a category.
     *
     * @param integer $parentId
     * The parent id of the category.
     * @return string The hierarchy string.
     */
    public function getHieraechyLevel($parentId) {
        $category = new Category ();
        $parentLevel = $category->where ( 'id', $parentId )->value ( 'level' );
        return $parentLevel . '/' . $parentId;
    }
    /**
     * Function to get hierarchy level of a category.
     *
     * @param integer $parentId
     * The parent id of the category.
     * @return string The hierarchy string.
     */
    public function getHieraechyCountLevel($parentId) {
        $category = new Category ();
        $parentLevel = $category->where ( 'id', $parentId )->value ( 'level' );
        return $parentLevel + 1;
    }
    /**
     * Fetch users to display in admin block.
     *
     * @vendor Contus
     *
     * @package Video
     * @return response
     */
    public function getCategories($status) {
        return $this->_category->filter ( $status )->paginate ( 10 );
    }
    /**
     * Fetch user to edit.
     *
     * @vendor Contus
     *
     * @package Video
     * @return response
     */
    public function getCategory($id) {
        return $this->_category->find ( $id );
    }
    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     *
     * @vendor Contus
     *
     * @package Video
     * @return Contus\Video\Repositories\BaseRepository
     */
    public function prepareGrid() {
        $this->setGridModel ( $this->_category )->setEagerLoadingModels ( [ 'parent_category.parent_category','child_category','videocategory' => function ($query) {
            $query->whereHas ( 'video', function ($query) {
                $query->where ( StringLiterals::IS_ARCHIVED, 0 );
            } );
        } ] );
        return $this;
    }

    /**
     * Function to apply filter for search of categories grid
     *
     * @param mixed $builderCategories
     * @return \Illuminate\Database\Eloquent\Builder $builderCategories The builder object of categories grid.
     */
    protected function searchFilter($builderCategories) {
        $searchRecordCategories = $this->request->has ( StringLiterals::SEARCHRECORD ) && is_array ( $this->request->input ( StringLiterals::SEARCHRECORD ) ) ? $this->request->input ( StringLiterals::SEARCHRECORD ) : [ ];
        $title = $is_active = null;
        extract ( $searchRecordCategories );

        /**
         * Check if the title of the category is present in the category search.
         * If yes, then use it in filter.
         */
        if ($title) {
            $builderCategories = $builderCategories->where ( StringLiterals::TITLE, 'like', '%' . $title . '%' );
        }

        /**
         * Check if the status of the category is present in the category search.
         * If yes, then use it in filter.
         */
        if (is_numeric ( $is_active )) {
            $builderCategories = $builderCategories->where ( StringLiterals::ISACTIVE, $is_active );
        }

        return $builderCategories;
    }

    /**
     * Check the collection name provied is unique.
     * check only if the request has the expected param
     *
     * @vendor Contus
     *
     * @package User
     * @param int $id
     * @return boolean
     */
    public function isUniqueCategories($id = null) {
        if ($this->request->has ( $this->requestedCategories )) {
            $uniqueQuery = $this->_category->where ( StringLiterals::TITLE, $this->request->get ( $this->requestedCategories ) );
            if ($id) {
                $uniqueQuery->where ( 'id', '!=', $id );
            }

            return $uniqueQuery->count () == 0;
        }
        return false;
    }

    /**
     * Function used to retrieve parent catgory with its child category in (tree structure) format
     *
     * @return string
     */
    public function getAllCategoryList() {
        $categories = Category::where ( 'parent_id', 0 )->where ( 'is_deletable', 1 )->select ( 'id', StringLiterals::TITLE )->get ();
        $categoryList = '<ul>';

        if (sizeof ( $categories ) > 0) {
            foreach ( $categories as $category ) {
                $categoryList = $categoryList . '<li id="category_id_' . $category->id . '"><input type="radio" name="parent_id" data-ng-model="catgridCtrl.category.parent_id" value=' . $category->id . '><span><i class="fa fa-folder"></i>' . $category->title . '</span>';
                $categoryStatus = $this->hasChild ( $category->id );

                if (! empty ( $categoryStatus )) {
                    $categoryList .= $categoryStatus;
                }
                $categoryList .= "</li>";
            }
        }
        return $categoryList .= "</ul>";
    }
    /**
     * Get the child category for the playlist
     *
     * @return string
     */
    public function getChildCategoryList() {
        $categories = Category::where ( 'parent_id', 0 )->where ( 'is_deletable', 1 )->select ( 'id', StringLiterals::TITLE )->get ();
        if (sizeof ( $categories ) > 0) {
            foreach ( $categories as $category ) {
                $categoryStatus = $this->hasparentChild ( $category->id );
            }
        }
        return $categoryStatus;
    }
    /**
     * Get child category slug
     *
     * @param unknown $id
     */
    public function hasparentChild($id) {
        return Category::where ( 'parent_id', $id )->select ( 'id', StringLiterals::TITLE )->get ();
    }
    /**
     * Function used to retrieve child category in tree structure format
     *
     * @param int $id
     * @return string
     */
    public function hasChild($id) {
        $categories = Category::where ( 'parent_id', $id )->select ( 'id','level', StringLiterals::TITLE )->get ();
        $categoryList = '';

        if (sizeof ( $categories ) > 0) {
            $categoryList .= "<ul>";
            foreach ( $categories as $category ) {
                if($category->level == 2){
                    $categoryList .= '<li id="category_id_' . $category->id . '"><span><i class="fa fa-folder"></i>' . $category->title . '</span>';

                }else{
                $categoryList .= '<li id="category_id_' . $category->id . '"><input type="radio" name="parent_id" data-ng-model="catgridCtrl.category.parent_id" value=' . $category->id . '><span><i class="fa fa-folder"></i>' . $category->title . '</span>';
                }
                $categoryStatus = $this->hasChild ( $category->id );
                if (! empty ( $categoryStatus )) {
                    $categoryList .= $categoryStatus;
                }
                $categoryList .= "</li>";
            }
            $categoryList .= "</ul>";
        }
        return $categoryList;
    }

    /**
     * Repository function to get the category breadcrumb
     *
     * @param integer $id.
     * @return variable
     */
    public function getBreadcrumb($id) {
        $categoryLevel = $this->getCategory ( $id );
        $categoryBreadcrumb = [ ];
        if ($categoryLevel->parent_id != 0) {
            $parentCategory = $this->_category->find ( $categoryLevel->parent_id );
            $categoryBreadcrumb ['parent'] ['id'] = $categoryLevel->parent_id;
            $categoryBreadcrumb ['parent'] ['name'] = $parentCategory->title;
        }

        $categoryBreadcrumb ['child'] = $categoryLevel->title;
        return $categoryBreadcrumb;
    }

    /**
     * Repository function to get the parentcategory list
     *
     * @param integer $id
     * @return variable
     */
    public function getParentCategory($id) {
        $categoryData = $this->_category->find ( $id );
        $categoryData = explode ( '/', $categoryData->level );
        $parentCategoryTitle = [ ];
        $parentcategoryData = [ ];
        foreach ( $categoryData as $value ) {
            // code...
            if ($value != 0) {
                $parentcategoryTitleData = $this->_category->select ( 'id', StringLiterals::TITLE )->find ( $value );
                $parentCategoryTitle [$parentcategoryTitleData->id] = $parentcategoryTitleData->title;
                $parentcategoryData [] = $this->_category->find ( $value );
            }
        }
        return array ('parentcategoryTitle' => $parentCategoryTitle,'parentcategoryData' => $parentcategoryData );
    }

    /**
     * Repository function to get the category related videos list
     *
     * @param integer $id
     * @return variable
     */
    public function getVideoCategories($id) {
        $this->_category = $this->_category->find ( $id );
        if (is_null ( $this->_category )) {
            return $this->_category;
        }

        return [ 'category' => $this->_category,'videos' => $this->_category->videos ()->with ( [ 'transcodedvideos.presets','videocategory.category','recent' ] )->where ( 'is_archived', 0 )->paginate ( 10 )->toArray () ];
    }

    /**
     * Repository function to get the childcategory list
     *
     * @param integer $id
     * @return variable
     */
    public function getCategoryWithChild($id) {
        return $this->_category->with ( 'child_category' )->findOrFail ( $id );
    }
    /**
     * Function to get all categories.
     *
     * @return array All categories.
     */
    public function getAllCategories($slug = '') {
        $subcatvalue = [ ];
        if ($slug) {
            $categoryinfo = $this->_category->where ( $this->getKeySlugorId (), $slug )->where ( 'is_active', 1 )->where ( 'parent_id', 0 )->with ( 'child_category.child_category' )->get ();
        } else {
            $categoryinfo = $this->_category->where ( 'parent_id', 0 )->where ( 'is_active', 1 )->with ( 'child_category.child_category' )->get ()->toArray ();
        }
        if (count ( $categoryinfo ) > 0) {
            foreach ( $categoryinfo as $value ) {
                if (count ( $value ['child_category'] ) > 0) {
                    $subcatvalue = $subcatvalue + $this->getChildCategoryEach ( $value );
                }
            }
        }
        return $subcatvalue;
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($builder) {
        $filters = $this->request->input ( 'filters' );
        if (! empty ( $filters )) {
            foreach ( $filters as $value ) {
                if($value == 'live_videos'){
                    $builder->whereNotNull('preference_order')->orderBy('preference_order');
                }
            }
        }
        $builder->where ( 'is_deletable', 1 );
        return $builder;
    }

     /**
     * Function to get all category lists.
     *
     * @return array All categories.
     */
    
    
    public function getCategoriesLists($slug = '') {
        if ($slug) {
            $categoryinfo = $this->_category->where ( 'slug', $slug )->where ( 'is_active', 1 )->where ( 'parent_id', 0 )->with ( 'child_category.child_category' )->get ()->toArray ();
        } else {
            $categoryinfo = $this->_category->where ( 'parent_id', 0 )->where ( 'is_active', 1 )->with ( 'child_category.child_category' )->get ()->toArray ();
        }
        
        return $categoryinfo;
    }
   
}