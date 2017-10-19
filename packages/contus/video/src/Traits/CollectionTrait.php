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
use Contus\Video\Models\Video;
use Contus\Video\Repositories\FrontVideoRepository;
use Contus\Base\Controller;



trait CollectionTrait{
 
 /**
  * Repository function to delete custom thumbnail of a video.
  *
  * @param integer $id
  * The id of the video.
  * @return boolean True if the thumbnail is deleted and false if not.
  */
 public function deleteThumbnail($id){
  $video =new video();
  /**
   * Check if video id exists.
   */
  if (!empty ($id)) {
   $video = $video->findorfail($id);
   /**
    * Delete the thumbnail image using the thumbnail path field from the database.
    */
   $video->thumbnail_image = '';
   $video->thumbnail_path = '';
   $video->save();
   return true;
  } else {
   return false;
  }
 }
  
 /**
  * Repository function to delete subtitle of a video.
  *
  * @param integer $id
  * The id of the video.
  * @return boolean True if the subtitle is deleted and false if not.
  */
 public function deleteSubtitle($id)
 {
  $video =new video();
  
  /**
   * Check if video id exists.
   */
  if (!empty ($id)) {
   $video = $video->findorfail($id);
   /**
    * Delete the subtitle image using the subtitle path field from the database.
    */
   $video->mp3 = '';
   $video->subtitle_path = '';
   $video->save();
   return true;
  } else {
   return false;
  }
 }
 /**
  * Function to fetch all videos
  *
  * @return json
  */
 public function liveVideoNotification() {
  $fetch ['live'] = FrontVideoRepository::getLiveVideoNotification ();
  return Controller::getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ),'response' => $fetch ] );
 }
 /**
  * Funtion to send the related search key for search funtionlaity
  *
  * @return json
  */
 public function searchRelatedVideos() {
  $fetch ['videos'] = FrontVideoRepository::getallVideo ( false );
  if (array_filter ( $fetch )) {
   return Controller::getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ),'response' => $fetch ] );
  } else {
   return Controller::getErrorJsonResponse ( [ ], trans ( 'video::videos.fetch.error' ) );
  }
 }
 /**
  * Function to add the video play tracking list
  *
  * @param id|string $slug
  */
 public function videoPlayTracker($slug) {
  (FrontVideoRepository::videoPlayTracker ( $slug )) ? Controller::getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ) ] ) : Controller::getErrorJsonResponse ( [ ], trans ( 'video::videos.fetch.error' ) );
 }
 
 
 /**
  * This function used to get the all the scheduled and recorded videos
  */
 public function AllLiveVideos() {
  $fetch ['all_live_videos'] = FrontVideoRepository::getAllLiveVideos ();
  if (array_filter ( $fetch )) {
   return Controller::getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ),'response' => $fetch ] );
  } else {
   return Controller::getErrorJsonResponse ( [ ], trans ( 'video::videos.fetch.error' ) );
  }
 }
 
}