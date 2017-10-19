<?php

/**
 * Collection Controller
 *
 * To manage the Collections such as create, edit and delete
 *
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Api\Controllers\Frontend;

use Contus\Video\Repositories\CollectionRepository;
use Contus\Base\ApiController;
use Contus\Video\Repositories\CategoryRepository;
use Contus\Video\Repositories\GroupRepository;
use Contus\Video\Models\Group;
use Contus\Customer\Models\MypreferencesVideo;
use Contus\Customer\Repositories\SubscriptionRepository;

class CollectionController extends ApiController {
    /**
     * Constructer method which defines the objects of the classes used.
     *
     * @param object $collectionRepository
     */
    public function __construct(CollectionRepository $collectionRepository, CategoryRepository $catreposity,SubscriptionRepository $subscription) {
        parent::__construct ();
        $this->repository = $collectionRepository;
        $this->category = $catreposity;
        $this->groups = new GroupRepository(new Group(), new MypreferencesVideo());
        $this->repository->setRequestType ( static::REQUEST_TYPE );
        $this->subscription = $subscription;
    }

    /**
     * Function to fetch the purticular exam collection based on slug or id
     *
     * @return \Contus\Base\response
     */
    public function browseCategoryExams() {
        $data = $this->repository->getExamVideoCollections ( $this->request->slug );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::playlist.exam_list' ),'response' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.errorExams' ) );
    }
    /**
     * Function to fetch all Exam related collections
     *
     * @return \Contus\Base\response
     */
    public function getAllExams() {
        $data = $this->repository->getExamVideoCollections ();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::playlist.exam_list' ),'response' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.errorExams' ) );
    }
    /**
     * Function to get list of groups in a exams
     *
     * @param string|id $examIdorSlug
     * @return \Contus\Base\response
     */
    public function getAllGroups($examIdorSlug) {
        $data = $this->repository->getAllGroups ( $examIdorSlug );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::playlist.exam_list' ),'response' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.errorExams' ) );
    }
    /**
     * Function to get list of all videos based on group id
     *
     * @param unknown $groupIdorSlug
     * @return \Contus\Base\response
     */
    public function getAllVideos($groupIdorSlug){
        $data = $this->groups->getAllVideos($groupIdorSlug);
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::playlist.exam_list' ),'response' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.errorExams' ) );
    }
    /**
     * Function to get list of all videos based on group id
     *
     * @param unknown $groupIdorSlug
     * @return \Contus\Base\response
     */
    public function getRecommendedVideosSkip($skip){
        $data = $this->groups->getRecommendedVideos ($skip);
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::playlist.exam_list' ),'response' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.errorExams' ) );
    }
    /**
     * Function to get list of all videos based on group id
     *
     * @param unknown $groupIdorSlug
     * @return \Contus\Base\response
     */
    public function getRecommendedVideos(){
        $data = $this->groups->getRecommendedVideos ();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::playlist.exam_list' ),'response' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.errorExams' ) );
    }
    /**
     * Function to send email with payment link for customer
     *
     * @param srring/id $customerId
     * @return \Contus\Base\response
     */
    public function sendPaymentlink(){
        $data = $this->subscription->sendPaymentlink ();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::dashboard.paymentLink' ),'response' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::dashboard.errorPaymentlink' ) );
    }
}
