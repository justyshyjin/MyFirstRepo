<?php

/**
 * StaticContent Controller
 * To manage the functionalities related to the static content gird api methods
 *
 * @vendor Contus
 *
 * @package cms
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Api\Controllers\Cms;

use Contus\Base\ApiController;
use Contus\Cms\Repositories\staticcontentsRepository;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;
use Contus\Cms\Repositories\StaticContentRepository;

class StaticContentController extends ApiController {
    /**
     * class property to hold the instance of staticcontentsRepository
     *
     * @var \Contus\Base\Repositories\staticcontentsRepository
     */
    public $staticContentRepository;
    /**
     * Construct method
     */
    public function __construct(StaticContentRepository $staticContentRepository,UploadRepository $uploadRepository) {
        parent::__construct ();
        $this->repository = $staticContentRepository;
        $this->uploadRepository = $uploadRepository;
    }

    /**
     * To get the Static content info.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo() {
        $data = $this->repository->getAlltheStaticContents ();
        unset ( $data->id );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::subscription.showError' ) );
    }

    /**
     * To get the Static content info.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStaticData($id) {
        $data = $this->repository->getStaticContent ( $id );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'response' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::subscription.showError' ) );
    }

    /**
     * Store a newly created Static content.
     *
     * @return \Illuminate\Http\Response
     */
    public function postAdd() {
        $isCreated = false;

        if ($this->repository->addOrUpdateStaticContents ()) {
            $isCreated = true;
            $this->request->session ()->flash ( StringLiterals::SUCCESS, trans ( 'cms::staticcontent.adds.success' ) );
        }

        return ($isCreated) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::staticcontent.adds.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::staticcontent.adds.error' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function postEdit($staticId) {
        $isCreated = false;
        if ($this->repository->addOrUpdateStaticContents ( $staticId )) {
            $isCreated = true;
            $this->request->session ()->flash ( 'success', trans ( 'cms::staticcontent.updates.success' ) );
        }

        return ($isCreated) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::staticcontent.updates.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::staticcontent.updates.error' ) );
    }
    /**
     * Get the banner image for the static page id.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function postStaticBannerImage(){  
        $tempImageInfo = $this->uploadRepository->setModelIdentifier ( UploadRepository::MODEL_IDENTIFIER_STATICBANNER )->tempPrepare ()->tempUpload ();  
        return empty ( $tempImageInfo ) ? $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.messsage.unable_to_upload' ) ) : $this->getSuccessJsonResponse ( [ 'info' => array_shift ( $tempImageInfo ) ] );
    }

    /**
     * Controller function to delete Static Content Banner image.
     *
     * @param integer $id
     * The id of the user.
     * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
     */
    public function postDeleteStaticBannerImage($id){

        $isStaticBannerImageDeleted = false;
        try {
            /**
             * Call the deleteProfileImage repository method to delete profile image of a user.
             */
            if ($this->repository->deleteStaticBannerImage ( $id )) { 
                $isStaticBannerImageDeleted = true;
                $this->request->session ()->flash ( StringLiterals::SUCCESS, trans ( 'user::user.message.profile-image-delete-success' ) );
            }
        }
        catch ( Exception $e ) {
            /**
             * Handle the error exception when the user of the Static Banner image does not exist.
             */
            $this->request->session ()->flash ( StringLiterals::ERROR, trans ( 'user::user.user_not_exist' ) );
            $isStaticBannerImageDeleted = true;
        }
        /**
         * If the Static Banner image is deleted successfully, return the success response.
         * If the Static Banner image is not deleted successfully, return the failure resposne.
         */
        return ($isStaticBannerImageDeleted) ? $this->getSuccessJsonResponse ( [ StringLiterals::MESSAGE => trans ( 'user::user.message.profile-image-delete-success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'user::user.message.profile-image-delete-error' ) );
    

    }
}
