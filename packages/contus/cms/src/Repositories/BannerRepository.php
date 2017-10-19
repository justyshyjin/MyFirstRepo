<?php

/**
 * Banner Repository
 *
 * To manage the functionalities related to the Banner Management
 *
 * @vendor Contus
 *
 * @package Cms
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Base\Helpers\StringLiterals;
use Contus\Cms\Models\Banner;

class BannerRepository extends BaseRepository {

    /**
     * Class property to hold the key which hold the Banner object
     *
     * @var object
     */
    protected $_banner;
    /**
     * Construct method
     *
     * @param Contus\Cms\Models\Banner $bannerContent
     */
    public function __construct(Banner $bannerContent) {
        parent::__construct ();
        $this->_banner = $bannerContent;
    }
    /**
     * Store a newly created banners content or update the banner content.
     *
     * @param $id input
     * @return boolean
     */
    public function addOrUpdateBannerContents($id = null) { 
        if (! empty ( $id )) {
            $bannerContent = $this->_banner->find ( $id ); 
            if (! is_object ( $bannerContent )) {
                return false;
            }
            if (is_string(app ( 'request' )->banner_image) && isset(app ( 'request' )->banner_image) && filter_var ( app ( 'request' )->banner_image, FILTER_VALIDATE_URL ) === false) {
                $bannerContent->banner_image = config ()->get ( 'settings.aws-settings.aws-general.aws_s3_image_base_url' ) . '/' . app ( 'request' )->banner_image;
            }           
            $this->setRules ( [ 'title' => 'filled','is_active' => 'filled|boolean','type' => 'filled', 'category' => 'required', 'imageUrl' => 'required' ] ); 
         $bannerContent->category_title = $this->request->category; 
         $bannerContent->url = $this->request->imageUrl; 
         $bannerContent->category_title = $this->request->category;
         
         $bannerContent->save();  
        } else {
            $this->setRules ( [ 'title' => 'required', 'category' => 'required', 'imageUrl' => 'required','type' => 'required' ] );
            $bannerContent = new Banner ();
            $bannerContent->is_active = 1;
            $bannerContent->creator_id = $this->authUser->id;
        }
        $this->_validate ();
        $bannerContent->fill ( $this->request->except ( '_token', 'banner_image' ) ); 
        $bannerContent->category_title = $this->request->category; 
        $bannerContent->url = $this->request->imageUrl;
        $bannerContent->category_title = $this->request->category;
        
        return ($bannerContent->save ()) ? 1 : 0;
    }
    /**
     * Get banners contents like(images or video)
     *
     * @param int $id
     * @return object
     */
    public function getBanners() {
        return $this->_banner->paginate ( 10 )->toArray ();
    }

    /**
     * This function used to get the recent banners list
     */
    public function getBannerlists() {
        return $this->_banner->orderBy ( 'id', 'DESC' )->take ( 8 )->get ();
    }
    /**
     * Delete one Banner using ID
     *
     * @param int $id
     * @return boolean
     */
    public function deleteStaticContent($id) {
        $data = $this->_banner->find ( $id );
        if ($data) {
            $data->delete ();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the home banner image
     */
    public function getBannerImage() {
        return $this->_banner->where ( 'is_active', 1 )->select ( 'banner_image', 'type','category_title','title','url' )->first ();
    }
    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     *
     * @return Contus\User\Repositories\BaseRepository
     */
    public function prepareGrid() {
        $this->setGridModel ( $this->_banner );

        return $this;
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($bannerBuilder) {
        /*
         * updated the all user record only an superadmin user.
         */
        if ($this->authUser->id != 1) {
            $bannerBuilder->where ( 'id', $this->authUser->id )->orWhere ( 'parent_id', $this->authUser->id );
        }

        return $bannerBuilder;
    }

    /**
     * Function to apply filter for search of latestnews grid
     *
     * @param mixed $builderStatic
     * @return \Illuminate\Database\Eloquent\Builder $builderUsers The builder object of users grid.
     */
    protected function searchFilter($bannerStatic) {
        $searchbannerRecordUsers = $this->request->has ( StringLiterals::SEARCHRECORD ) && is_array ( $this->request->input ( StringLiterals::SEARCHRECORD ) ) ? $this->request->input ( StringLiterals::SEARCHRECORD ) : [ ];
        /**
         * Loop the search fields of users grid and use them to filter search results.
         */

        foreach ( $searchbannerRecordUsers as $key => $value ) {
            if ($key == StringLiterals::ISACTIVE && $value == 'all') {
                continue;
            }

            $bannerStatic = $bannerStatic->where ( $key, 'like', "%$value%" );
        }

        return $bannerStatic;
    }
    /**
     * Get headings for grid
     *
     * @return array
     */
    public function getGridHeadings() {
        return [ StringLiterals::GRIDHEADING => [ [ 'name' => trans ( 'cms::testimonial.name' ),StringLiterals::VALUE => 'name','sort' => false ],[ 'name' => trans ( 'cms::testimonial.type' ),StringLiterals::VALUE => '','sort' => false ],[ 'name' => trans ( 'cms::testimonial.bannerImage' ),StringLiterals::VALUE => '','sort' => false ],[ 'name' => trans ( 'cms::staticcontent.created_at' ),StringLiterals::VALUE => '','sort' => false ],[ 'name' => trans ( 'cms::smstemplate.action' ),StringLiterals::VALUE => '','sort' => false ] ] ];
    }
}