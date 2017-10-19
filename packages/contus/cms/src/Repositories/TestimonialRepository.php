<?php

/**
 * Testimonial Repository
 *
 * To manage the functionalities related to the Student Testimonials
 *
 * @vendor Contus
 * @package Cms
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Base\Helpers\StringLiterals;
use Contus\Cms\Models\Testimonials;

class TestimonialRepository extends BaseRepository {

    /**
     * Class property to hold the key which hold the testimonial content object
     *
     * @var object
     */
    protected $_testimonial;
    /**
     * Construct method
     * @param Contus\Cms\Models\Testimonials $testimonialContent
     */
    public function __construct(Testimonials $testimonialContent) {
        parent::__construct ();
        $this->_testimonial = $testimonialContent;
    }
    /**
     * Store a newly created testimonial content or update the testimonial content.
     *
     * @param $id input
     * @return boolean
     */
    public function addOrUpdateStaticContent($id = null) {
        if (! empty ( $id )) {
            $staticContent = $this->_testimonial->find ( $id );
            if (! is_object ( $staticContent )) {
                return false;
            }
            $this->setRules ( [ 'name' => 'filled','is_active' => 'filled|boolean','description' => 'filled' ] );

        } else {
            $this->setRules ( [ 'name' => 'required','description' => 'required' ] );
            $staticContent = new Testimonials();
            $staticContent->is_active = 1;
            $staticContent->creator_id = $this->authUser->id;
        }
        $this->_validate ();
        $staticContent->fill ( $this->request->except ( '_token' ) );
        return ($staticContent->save ()) ? 1 : 0;
    }
    /**
     * Get testimonial contents
     * @param int $id
     * @return object
     */
    public function getStaticContent() {
        return $this->_testimonial->paginate ( 10 )->toArray ();
    }

    /**
     * This function used to get the recent testimonial list
     */
    public function getTestimoniallists(){
      return $this->_testimonial->orderBy('id','DESC')->take(8)->get();

    }

   /**
    * This function used to display the testimonial list in api
    */
    public function getAllTestimonials(){
      return $this->_testimonial->orderBy('id','DESC')->take(8)->get();
    }


    /**
     * fetches one Testimonial content using slug
     *
     * @param int $subscriptionSlug
     * @return object
     */
    public function getStaticcontentSlug($subscriptionSlug) {
      return $this->_testimonial->where ( 'slug', $subscriptionSlug )->select('id','title','slug','content','is_active')->first ();
    }

    /**
     * Get all static content
     * @return array
     */
    public function getAllStaticContents() {
        return $this->_testimonial->paginate ( 10 )->toArray ();
    }
    /**
     * Delete one static content using ID
     *
     * @param int $id
     * @return boolean
     */
    public function deleteStaticContent($id) {
        $data = $this->_testimonial->find ( $id );
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
     * @return Contus\User\Repositories\BaseRepository
     */
    public function prepareGrid() {
        $this->setGridModel ( $this->_testimonial );

        return $this;
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($staticBuilder) {
        /*
         * updated the all user record only an superadmin user.
         */
        if ($this->authUser->id != 1) {
            $staticBuilder->where ( 'id', $this->authUser->id )->orWhere ( 'parent_id', $this->authUser->id );
        }

        return $staticBuilder;
    }

    /**
     * Function to apply filter for search of latestnews grid
     * @param mixed $builderStatic
     * @return \Illuminate\Database\Eloquent\Builder $builderUsers The builder object of users grid.
     */
    protected function searchFilter($builderStatic) {
        $searchRecordUsers = $this->request->has ( StringLiterals::SEARCHRECORD ) && is_array ( $this->request->input ( StringLiterals::SEARCHRECORD ) ) ? $this->request->input ( StringLiterals::SEARCHRECORD ) : [ ];
        /**
         * Loop the search fields of users grid and use them to filter search results.
         */

        foreach ( $searchRecordUsers as $key => $value ) {
            if ($key == StringLiterals::ISACTIVE && $value == 'all') {
                continue;
            }

            $builderStatic = $builderStatic->where ( $key, 'like', "%$value%" );
        }

        return $builderStatic;
    }
    /**
     * Get headings for grid
     * @return array
     */
    public function getGridHeadings() {
        return [ StringLiterals::GRIDHEADING => [ [ 'name' => trans ( 'cms::testimonial.name' ),StringLiterals::VALUE => 'name','sort' => false ],
        [ 'name' => trans ( 'cms::testimonial.image' ),StringLiterals::VALUE => '','sort' => false ],
        [ 'name' => trans ( 'cms::staticcontent.created_at' ),StringLiterals::VALUE => '','sort' => false ],[ 'name' => trans ( 'cms::staticcontent.status' ),StringLiterals::VALUE => 'is_active','sort' => false ],[ 'name' => trans ( 'cms::smstemplate.action' ),StringLiterals::VALUE => '','sort' => false ] ] ];
    }
}