<?php

/**
 * Upload To S3 Scheduler
 *
 * @name UploadToS3Scheduler
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Schedulers;

use Contus\Base\Schedulers\Scheduler;
use Aws\S3\S3Client;
use Aws\ElasticTranscoder\ElasticTranscoderClient;
use Contus\Video\Models\Video;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Video\Models\TranscodedVideo;
use Contus\Video\Models\VideoPreset;
use Exception;
use Contus\Video\Helpers\DeletedVideoException;

class UploadToS3Scheduler extends Scheduler
{

    /**
     * Class property to hold Video instance
     *
     * @var \Contus\Video\Models\Video
     */
    protected $video = null;

    /**
     * Class property to hold AWSUploadRepository instance
     *
     * @var \Contus\Video\Repositories\AWSUploadRepository
     */
    protected $awsRepository = null;

    /**
     * Class intializer
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->video = new Video();
        $this->awsRepository = new AWSUploadRepository(new TranscodedVideo(), new VideoPreset());
    }

    /**
     * Scheduler frequency
     *
     * @param \Illuminate\Console\Scheduling\Event $event
     * @return void
     */
    public function frequency(\Illuminate\Console\Scheduling\Event $event)
    {
        $event->everyMinute();
    }

    /**
     * Scheduler call method
     * actual execution go's here
     *
     * @return \Closure
     */
    public function call()
    {
        return function () {
            $transcodeType = config()->get('settings.general-settings.site-settings.video_transcode_type');
            $submittedJobs = Video::where('job_status', 'Video Uploaded')->where('fine_uploader_name', '!=', '')
                ->select('id', 'fine_uploader_name', 'fine_uploader_uuid', 'creator_id')
                ->get();
            foreach ($submittedJobs as $submittedJob) {
                try {
                app('log')->info('Upload To S3 Scheduler');
                $extensionArray = explode('.', $submittedJob["fine_uploader_name"]);
                $extension = end($extensionArray);
                $fileSlug = $submittedJob["id"] . '-video-' . rand(100000, 999999);
                $filename = $fileSlug . '.' . $extension;
                $file = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $submittedJob["fine_uploader_uuid"] . DIRECTORY_SEPARATOR . $submittedJob["fine_uploader_name"]);
                
                    // Validate the file and delete it if it is not valid.
                    if (! $this->isValidFile($file)) {
                        $this->setErrorStatus($submittedJob);
                        continue;
                    }
                    // Change status of the video to Uploading before uploading.
                    
                    $getID3 = new \getID3();
                    $fileGetProperties = $getID3->analyze($file);
                    $uploadingVideo = $this->video->findorfail($submittedJob["id"]);
                    if ($uploadingVideo->is_archived === 1) {
                        throw new DeletedVideoException();
                    }
                    $uploadingVideo->video_duration = $fileGetProperties['playtime_string'];
                    $uploadingVideo->job_status = 'Uploading';
                    if (config()->get('settings.aws-settings.aws-general.aws_payer_hls') == 'Yes') {
                        $uploadingVideo->is_hls = 1;
                    }
                    $uploadingVideo->save();
                    $videoUrl = $this->awsRepository->uploadFileToS3($file, $filename);
                    if ($videoUrl) {
                        $videoData = array(
                            'id' => $submittedJob["id"],
                            'title' => $submittedJob["fine_uploader_name"],
                            'video_url' => $videoUrl,
                            'file_slug' => $fileSlug,
                            'extension' => $extension,
                            'fine_uploader_uuid' => $submittedJob["fine_uploader_uuid"],
                            'creator_id' => $submittedJob['creator_id']
                        );
                        $uploadingVideo->video_url = $videoUrl;
                        $uploadingVideo->job_status = 'Uploaded';
                        $uploadingVideo->save();                        
                        if ($transcodeType != 'FFMPEG') {
                            $this->saveNewVideoDetails($videoData);
                        }
                    }
                } catch (Exception $exception) {
                    app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
                    $this->setErrorStatus($submittedJob);
                }
            }
        };
    }

    /**
     * Function to save details of the uploaded video in the database and trigger transcoding for that video.
     *
     * @param array $videoData
     *            The details of the new video.
     * @return boolean True if video is saved successfully and False if not.
     */
    function saveNewVideoDetails($videoData)
    {
        /**
         * Get pipeline id of Elastic Transcoder from settings cache file.
         */
        $pipelineId = config()->get('settings.aws-settings.aws-general.aws_pipeline_id');
        
        /**
         * Trigger transcoding process of the video uploaded.
         */
        $inputFile = $videoData['file_slug'] . '.' . $videoData['extension'];
        $jobId = $this->awsRepository->transcodeFile($pipelineId, $inputFile, $videoData['file_slug'], $videoData['id'], $videoData['creator_id']);
        $isJobId = false;
        if ($jobId) {
            /**
             * Update job id in the videos table.
             */
            $video = $this->video->findorfail($videoData['id']);
            $video->pipeline_id = $pipelineId;
            $video->video_url = $videoData['video_url'];
            $video->job_id = $jobId;
            $video->job_status = 'Progressing';
            $video->save();
            $isJobId = true;
        }
        return $isJobId;
    }

    /**
     * Function to check if a file is valid video file or not.
     *
     * @param string $file
     *            The path of the file which is being validated.
     * @return boolean True if the file is valid and False if not.
     */
    function isValidFile($file)
    {
        $validFileTypes = [
            'video/mp4',
            'video/quicktime',
            'video/avi',
            'video/x-ms-wmv',
            'video/msvideo',
            'video/x-msvideo'
        ];
        return (in_array(mime_content_type($file), $validFileTypes)) ? 1 : 0;
    }

    /**
     * FUnction to delete invalid file and set the status of the video to error.
     *
     * @param array $submittedJob
     *            The details of the file.
     */
    function setErrorStatus($submittedJob)
    {
        $video = $this->video->findorfail($submittedJob["id"]);
        $video->job_status = 'Error';
        $video->save();
        
        $filePath = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $submittedJob["fine_uploader_uuid"] . DIRECTORY_SEPARATOR . $submittedJob["fine_uploader_name"]);
        $folderPath = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $submittedJob["fine_uploader_uuid"]);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        if (file_exists($folderPath)) {
            rmdir($folderPath);
        }
    }
}