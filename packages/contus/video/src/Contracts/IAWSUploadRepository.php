<?php

/**
 * Implements of IAWSUploadRepository
 *
 * Inteface for implementing the AWSUploadRepository modules and functions  
 * 
 * @name       IAWSUploadRepository
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Contracts;

interface IAWSUploadRepository {
 /**
  * Function to get AWS client instance.
  */
 public function getAWSClient($clientType);
 /**
  * Function to upload a file to S3 bucket.
  *
  * @param string $file
  *         The file to be uploaded with its path.
  * @param string $bucket
  *         The name of the S3 bucket.
  * @param string $key
  *         The name of the output file.
  * @return boolean True on success and False on failure.
  */
 public function uploadFileToS3($file, $key);
 /**
  * Function to transcode a file using AWS Elastic transcoder.
  *
  * @param string $pipelineId
  *         The pipeline id of the AWS Elastic Transcoder.
  * @param string $inputFile
  *         The name of the input file in the S3 bucket.
  * @param string $outputSlug
  *         The output slug which will be appended to the name of the output files.
  * @param integer $videoID
  *         The id of the video in the database.
  * @return string|bool The job id returned from the elastic transcoder on success and False on failure.
  */
 public function transcodeFile($pipelineId, $inputFile, $outputSlug, $videoID, $creatorID);
}
