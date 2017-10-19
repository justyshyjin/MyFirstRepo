<?php
/**
 * Category Controller
 *
 * To manage the video categories.
 *
 * @name       Category Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Api\Controllers\Admin;

use Illuminate\Http\Request;
use Contus\Base\ApiController;
use Contus\Video\Repositories\LiveStreamRepository;

class LiveStreamController extends ApiController {
  /**
   * Construct method
   */
  public function __construct() {
    parent::__construct ();
    $this->repository = new LiveStreamRepository ();
    $this->repository->setRequestType ( static::REQUEST_TYPE );
    
  }
  public function createlivestream(){
    $v = $this->repository->store ();
   if($v == 'success'){
     return $this->getSuccessJsonResponse ( [ 'message' => 'Live stream added successfully' ] );
   }else{
     return $this->getErrorJsonResponse ( [ ], $v );
   }
  }
  /**
    * This method is use to start the live stream
    *
    * @return array Response
    */

   public function startLiveStream() {
   return $this->repository->startLiveStreamRepository ();
   }

    /**
    * This method is use to stop the live stream
    *
    * @return array Response
    */

   public function stopLiveStream() {
   return $this->repository->stopLiveStreamRepository ();
   }
   

     /**
    * This method is use to start the live stream
    *
    * @return array Response
    */

   public function statusLivestream() {
    $status  = $this->repository->statusLiveStreamRepository ();
    if($status === 'starting'){
      return $this->getSuccessJsonResponse ( [ 'message' => 'Live stream added successfully','response'=>'starting' ] );
    }else{
      return $this->getSuccessJsonResponse ( [ 'message' => 'Live stream added successfully','response'=>'started' ] );
    }
   }
   
   /**
    * This method is use to delete the live stream
    *
    * @return array Response
    */

   public function deleteLiveStream() {
   return $this->repository->deleteLiveStreamRepository ();
   }
   
   /**
    * This method is use to get recording video details
    *
    * @return array Response
    */

   public function liveStreamRecordings() {
   return $this->repository->liveStreamRecordingsRepository ();
   }
   
   /**
    * This method is use to delete recoring videos
    *
    * @return array Response
    */

   public function deleteRecordedLiveStream() {
   return $this->repository->deleteRecordingLivestreamRepository ();
   }
   
   /**
    * This method is use to get live stream options
    *
    * @return array Response
    */

   public function liveStreamOptions() {
   return $this->repository->liveStreamOptions ();
   }


   /**
    * This method is use to get the active live stream options
    *
    * @return array Response
    */

   public function activeLivestream() {
   return $this->getSuccessJsonResponse(['data' => $this->repository->activeLivestream ()]);

   } 
    /**
    * This method is use to get the active live stream options
    *
    * @return array Response
    */

   public function activeOnLivestream() {
   return $this->getSuccessJsonResponse(['data' => $this->repository->activeOnLivestream ()]);

   }
   /**
    * Function to get the hash tag twitter tweets
    * 
    * @return json
    */
   public function getHashTagTwitter() {
    /**
     * Get the hash tag twitter data
     * 
     * @return json
     */
    return $this->getSuccessJsonResponse(['data' => $this->repository->getHashTagTwitter()]);
   }
   
   /**
    * Function to get the live streaming time
    *
    * @return json
    */
   public function getLiveStreamingTime() {
    /**
     * Get the html data
     * 
     * @return json
     */
    return $this->getSuccessJsonResponse(['data' => $this->repository->getLiveStreamingTime() ]);
   }
   
    /**
    * Function to get the live streaming time
    *
    * @return json
    */
   public function clearCache() {
    /**
     * Get the html data
     * 
     * @return json
     */
    return $this->getSuccessJsonResponse(['data' => $this->clearAllCache() ]);
   }
   
   /**
    * Function to get the sync wowa live stream
    * 
    * @return json
    */
   public function syncwowzalive() {
     /**
      * Get the html data
      *
      * @return json
      */
     return $this->getSuccessJsonResponse(['data' => $this->repository->syncWowzaLive() ]);
   }
   
   /**
    * Function to get the manage cars live
    * 
    * @return json
    */
   public function manageCarsLive() {
       /**
        * Get the html data
        *
        * @return json
        */
       return $this->getSuccessJsonResponse(['data' => $this->repository->manageCarsLive() ]);
   }
   
   /**
    * This method is use to get the single livestream
    *
    * @return array Response
    */
   public function liveGetSingleRecord($id) {
       return $this->getSuccessJsonResponse(['data' => $this->repository->liveGetSingleRecord ($id)]);
   }
   
   /**
    * This method is use to get the single livestream
    *
    * @return array Response
    */
   public function livestreamUpdate() {
       return $this->getSuccessJsonResponse(['data' => $this->repository->livestreamUpdate ()]);
   }
}
