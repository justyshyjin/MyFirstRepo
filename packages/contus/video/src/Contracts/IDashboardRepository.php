<?php

/**
 * Implements of IDashboardRepository
 *
 * Inteface for implementing the DashboardRepository modules and functions  
 * 
 * @name       IDashboardRepository
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Contracts;

interface IDashboardRepository {
 /**
  * Function to get total number of videos in the application.
  *
  * @return integer Number of videos in the application.
  */
 public function getTotalNumberOfVideos();
 
 /**
  * Function to get total number of videos that are being progressed(transcoded).
  *
  * @return integer Total number of progressing videos.
  */
 public function getTotalProgressingVideos();
 
 /**
  * Function to get total number of presets available for transcoding.
  *
  * @return integer Total number of video presets.
  */
 public function getTotalVideoPresets();
 
 /**
  * Function to get number of active video presets.
  *
  * @return integer Total number of active presets.
  */
 public function getActiveVideoPresets();
 
 /**
  * Function to get statistics from AWS.
  *
  * @return array The statistics about AWS S3 bucket.
  */
 public function getAWSStats();
 
 /**
  * Function to get latest videos from the database.
  *
  * @return array Latest videos uploaded in the application.
  */
 public function getLatestVideos();
 
 /**
  * Function to get progressing videos from the database.
  *
  * @return array The videos which are being progressed(transcoded).
  */
 public function getProgressingVideos();
 
 /**
  * Function to get top categories(categories with most number of videos) from the database.
  *
  * @return array Top categories fetched from the database.
  */
 public function getTopCategories();
 
 /**
  * Function to get date wise video upload count which will be used to generate the chart in the dashboard.
  *
  * @return array Date wise video upload count.
  */
 public function getDateWiseVideoUploadCount();
}
