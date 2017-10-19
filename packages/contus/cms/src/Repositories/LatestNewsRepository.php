<?php

/**
 * Latest News Repository
 * To manage the functionalities related to the Customer module from Latest News Resource Controller
 *
 * @name LatestNewsRepository
 * @vendor Contus
 * @package Cms
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Cms\Models\LatestNews;
use Contus\Base\Helpers\StringLiterals;

class LatestNewsRepository extends BaseRepository {

    /**
     * Class property to hold the key which hold the Latest News object
     *
     * @var object
     */
    protected $_latestNews;
    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package Cms
     *
     * @param Contus\Cms\Models\LatestNews $latestNews
     */
    public function __construct(LatestNews $latestNews) {
        parent::__construct ();
        $this->_latestNews = $latestNews;
    }
    /**
     * Store a newly created Latest News or update the Latest News.
     * @vendor Contus
     *
     * @package Cms
     * @param $id input
     * @return boolean
     */
    public function addOrUpdateLatestNews($id = null) {
        if (! empty ( $id )) {
            $latestNews = $this->_latestNews->find ( $id );
            if (! is_object ( $latestNews )) {
                return false;
            }
            $this->setRules ( [ 'title' => 'filled','content' => 'filled','is_active' => 'filled|boolean','post_creator' => 'filled' ] );
            $latestNews->updator_id = $this->authUser->id;
        } else {
            $this->setRules ( [ 'title' => 'required|max:255','content' => 'required','post_creator' => 'required' ] );
            $latestNews = new LatestNews ();
            $latestNews->is_active = 1;
            $latestNews->creator_id = $this->authUser->id;
        }
        $this->_validate ();
        $latestNews->fill ( $this->request->except ( '_token' ) );
        return ($latestNews->save ()) ? 1 : 0;
    }
    /**
     * Get one Latest News using id
     * @vendor Contus
     *
     * @package Cms
     * @param int $id
     * @return object
     */
    public function getLatestNews($id) {
        return $this->_latestNews->find ( $id );
    }

    /**
     * Get the reply comments for blog
     *
     * @param unknown $blogSlug
     */
    public function getLatestNewsSlug($blogSlug) {
        return $this->_latestNews->where ( 'slug', $blogSlug )->where ( 'is_active', 1 )->select ( 'id', 'title', 'slug', 'content', 'created_at', 'post_creator', 'is_active','post_image' )->first ();
    }
    /**
     * Get all Latest News
     * @vendor Contus
     *
     * @package Cms
     * @return array
     */
    public function getAllLatestNews() {
        return $this->_latestNews->where ( 'is_active', 1 )->orderBy('id','desc')-> paginate ( 4 )->toArray ();
    }
    /**
     * Delete one Latest News using ID
     * @vendor Contus
     *
     * @package Cms
     * @param int $id
     * @return boolean
     */
    public function deleteLatestNews($id) {
        $data = $this->_latestNews->find ( $id );
        if ($data) {
            $data->delete ();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     * @vendor Contus
     *
     * @package Cms
     * @return Contus\User\Repositories\BaseRepository
     */
    public function prepareGrid() {
        $this->setGridModel ( $this->_latestNews );
        return $this;
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($latestNewsbuilder) {
        /*
         * updated the all user record only an superadmin user.
         */
        if ($this->authUser->id != 1) {
            $latestNewsbuilder->where ( 'id', $this->authUser->id )->orWhere ( 'parent_id', $this->authUser->id );
        }

        return $latestNewsbuilder;
    }

    /**
     * Function to apply filter for search of latestnews grid
     * @vendor Contus
     *
     * @package Cms
     * @param mixed $builderUsers
     * @return \Illuminate\Database\Eloquent\Builder $builderUsers The builder object of users grid.
     */
    protected function searchFilter($builderNews) {
        $searchRecordUsers = $this->request->has ( StringLiterals::SEARCHRECORD ) && is_array ( $this->request->input ( StringLiterals::SEARCHRECORD ) ) ? $this->request->input ( StringLiterals::SEARCHRECORD ) : [ ];
        /**
         * Loop the search fields of users grid and use them to filter search results.
         */

        foreach ( $searchRecordUsers as $key => $value ) {
            if ($key == StringLiterals::ISACTIVE && $value == 'all') {
                continue;
            }

            $builderNews = $builderNews->where ( $key, 'like', "%$value%" );
        }

        return $builderNews;
    }
    /**
     * Get headings for grid
     * @vendor Contus
     *
     * @package Cms
     * @return array
     */
    public function getGridHeadings() {
        return [ StringLiterals::GRIDHEADING => [ [ 'name' => trans ( 'cms::latestnews.title' ),StringLiterals::VALUE => 'name','sort' => false ],[ 'name' => trans ( 'cms::latestnews.post_creator' ),StringLiterals::VALUE => '','sort' => false ],[ 'name' => trans ( 'cms::latestnews.post_image' ),StringLiterals::VALUE => '','sort' => false ],[ 'name' => trans ( 'cms::latestnews.created_at' ),StringLiterals::VALUE => '','sort' => false ],[ 'name' => trans ( 'cms::latestnews.status' ),StringLiterals::VALUE => 'is_active','sort' => false ],[ 'name' => trans ( 'cms::latestnews.action' ),StringLiterals::VALUE => 'is_active','sort' => false ] ] ];
    }
}
