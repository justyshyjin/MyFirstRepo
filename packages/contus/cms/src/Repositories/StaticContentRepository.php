<?php

/**
 * Static Content Repository
 *
 * To manage the functionalities related to the Static Content Controller
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
use Contus\Cms\Models\StaticPages;
use Contus\Video\Repositories\AWSUploadRepository;

class StaticContentRepository extends BaseRepository {

    /**
     * Class property to hold the key which hold the static content object
     *
     * @var object
     */
    protected $_staticContent;
    /**
     * Construct method
     *
     * @param Contus\Cms\Models\StaticPages $staticContent
     */
    public function __construct(StaticPages $staticContent,AWSUploadRepository $awsRepository) {
        parent::__construct ();
        $this->_staticContent = $staticContent;
        $this->setRules ( [ 'title' => 'sometimes|required','is_active' => 'sometimes|required|boolean','content' => 'sometimes|required' ] );
        $this->awsRepo = $awsRepository;
    }
    /**
     * Store a newly created static content or update the static content.
     *
     * @param $id input
     * @return boolean
     */
    public function addOrUpdateStaticContents($id = null) {
        if (! empty ( $id )) {
            $contactUs = $this->_staticContent->find ( $id );
            if (! is_object ( $contactUs )) {
                return false;
            }
            $this->setRules ( [ 'title' => 'sometimes|required','is_active' => 'sometimes|required|boolean','content' => 'sometimes|required','banner_image' => 'sometimes|required' ] );
            $contactUs->updator_id = $this->authUser->id;
        } else {
            $this->setRules ( [ 'title' => 'required|max:255','content' => 'required' ] );
            $contactUs = new StaticPages ();
            $contactUs->is_active = 1;
            $contactUs->creator_id = $this->authUser->id;
            $contactUs->banner_image=$this->request->banner;
        }
        $contactUs->banner_image=$this->request->banner;
        $this->_validate ();
        $contactUs->fill ( $this->request->except ( '_token' ) );
        return ($contactUs->save ()) ? 1 : 0;
    }

    /**
     * Get one static content using id
     *
     * @param int $id
     * @return object
     */
    public function getStaticContent($id) {
        return $this->_staticContent->find ( $id );
    }

    /**
     * fetches one Static content using slug
     *
     * @param int $subscriptionSlug
     * @return object
     */
    public function getStaticcontentSlug($subscriptionSlug) {
        return $this->_staticContent->where ( 'slug', $subscriptionSlug )->where ( 'is_active', 1 )->select ( 'id', 'title', 'slug', 'content', 'is_active','banner_image' )->first ();
    }

    /**
     * Get all static content
     *
     * @return array
     */
    public function getAlltheStaticContents() {
        return $this->_staticContent->paginate ( 10 )->toArray ();
    }

    /**
     * Get all static content
     *
     * @return array
     */
    public function getAllStaticContents() {
        return $this->_staticContent->paginate ( 10 )->toArray ();
    }
    /**
     * Delete one static content using ID
     *
     * @param int $id
     * @return boolean
     */
    public function deleteStaticContent($id) {
        $data = $this->_staticContent->find ( $id );
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
     *
     * @return Contus\User\Repositories\BaseRepository
     */
    public function prepareGrid() {
        $this->setGridModel ( $this->_staticContent );
        return $this;
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($staticContentBuilder) {
        /*
         * updated the all user record only an superadmin user.
         */
        if ($this->authUser->id != 1) {
            $staticContentBuilder->where ( 'id', $this->authUser->id )->orWhere ( 'parent_id', $this->authUser->id );
        }

        return $staticContentBuilder;
    }

    /**
     * Function to apply filter for search of latestnews grid
     *
     * @param mixed $builderUsers
     * @return \Illuminate\Database\Eloquent\Builder $builderUsers The builder object of users grid.
     */
    protected function searchFilter($builderStatics) {
        $searchstaticcontentRecordUsers = $this->request->has ( StringLiterals::SEARCHRECORD ) && is_array ( $this->request->input ( StringLiterals::SEARCHRECORD ) ) ? $this->request->input ( StringLiterals::SEARCHRECORD ) : [ ];
        /**
         * Loop the search fields of users grid and use them to filter search results.
         */

        foreach ( $searchstaticcontentRecordUsers as $key => $value ) {
            if ($key == StringLiterals::ISACTIVE && $value == 'all') {
                continue;
            }

            $builderStatics = $builderStatics->where ( $key, 'like', "%$value%" );
        }

        return $builderStatics;
    }
    /**
     * Get headings for grid
     *
     * @return array
     */
    public function getGridHeadings() {
        return [ StringLiterals::GRIDHEADING => [ [ 'name' => trans ( 'cms::staticcontent.title' ),StringLiterals::VALUE => 'name','sort' => false ],
        [ 'name' => trans ( 'cms::staticcontent.updated_at' ),StringLiterals::VALUE => '','sort' => false ],[ 'name' => trans ( 'cms::smstemplate.action' ),StringLiterals::VALUE => '','sort' => false ] ] ];
    }

    /**
     * Repository function to delete static banner image.
     *
     * @param integer $id
     * The id of the static content.
     * @return boolean True if the static content image is deleted and false if not.
     */
    public function deleteStaticBannerImage($id)
    {
        /**
         * Check if static content id exists.
         */
        if (!empty ($id)) {
            $_staticContent = $this->_staticContent->findorfail($id);
            /**
             * Delete the profile image using the profile image path field from the database.
             */
            if (isset($_staticContent->banner_image) && $_staticContent->banner_image !== '') {
                $explodedStaticImage = array();
                $BannerImage = $_staticContent->banner_image;
                $explodedStaticImage = explode('/', $BannerImage);

                $this->awsRepo->deleteProfileImage_s3butcket($_staticContent->banner_image);

                /**
                 * Empty the profile_image and profile_image_path field in the database.
                 */

                $_staticContent->banner_image = '';
                $_staticContent->save();
                $deleteStatus = true;
            } else {
                $deleteStatus = false;
            }
            return $deleteStatus;
        } else {
            return false;
        }
    }
}