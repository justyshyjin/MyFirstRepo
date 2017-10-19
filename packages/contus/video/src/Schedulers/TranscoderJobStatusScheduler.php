<?php

/**
 * Transcoder Job Status Scheduler
 *
 * @name TranscoderJobStatusScheduler
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Video\Schedulers;

use Contus\Base\Schedulers\Scheduler;
use Aws\ElasticTranscoder\ElasticTranscoderClient;
use Contus\Video\Models\Video;
use Aws\S3\S3Client;
use Contus\Video\Models\TranscodedVideo;
use Contus\Video\Models\VideoPreset;
use Illuminate\Support\Facades\Storage;
use Contus\Video\Repositories\AWSUploadRepository;
use Carbon\Carbon;
use Contus\Video\Models\Ffmpegstatus;

class TranscoderJobStatusScheduler extends Scheduler
{
    /**
     * Class property to hold Video instance
     *
     * @var \Contus\Video\Models\Video
     */
    protected $video = null;
    protected $awsRepository = null;

    /**
     * Class intializer
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->video = new Video ();
        $this->awsRepository = new AWSUploadRepository (new TranscodedVideo (), new VideoPreset ());
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
            /**
             * In this function, all the unfinished jobs are retrieved from the database.
             * Their status are checked with AWS and updated in the database.
             */
            $unfinishedJobs = Video::where('job_status', 'Uploaded')->get();
            $transcodeType = config()->get('settings.general-settings.site-settings.video_transcode_type');
            if ($transcodeType == 'FFMPEG') {
                app('log')->info($transcodeType);
                $ffmpegStatus = Ffmpegstatus::get()->first();
                if ($ffmpegStatus->status) {
                    foreach ($unfinishedJobs as $single) {
                        $ffmpegStatus->status = 0;
                        $ffmpegStatus->save();
                        $this->video = new Video ();
                        $videoModel = $this->video->findorfail($single ['id']);
                        if ($videoModel->job_status !== 'Uploaded') {
                            exit;
                        }
                        $filePath = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $videoModel->fine_uploader_uuid . DIRECTORY_SEPARATOR . $videoModel->fine_uploader_name);
                        $folderPath = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $videoModel->fine_uploader_uuid);
                        $fileName = $videoModel->fine_uploader_name;
                        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                        $pathinfo = pathinfo($fileName);
                        $extension = $pathinfo['extension'];
                        $newname = $pathinfo['filename'];
                        $current_time = Carbon::now()->timestamp;
                        $newname = str_replace(' ', '_', $newname);
                        $newname = preg_replace('/[^A-Za-z0-9\-]/', '', $newname) . $current_time . '.' . $extension;
                        shell_exec("sudo chmod -R 777 $folderPath");
                        try {
                            rename($filePath, $folderPath . '/' . $newname);
                        } catch (\Exception $e) {
                            $videoModel->job_status = 'Error';
                            $videoModel->save();
                            $ffmpegStatus = Ffmpegstatus::get()->first();
                            $ffmpegStatus->status = 1;
                            $ffmpegStatus->save();
                        }
                        $videoModel->fine_uploader_name = $newname;
                        $videoModel->save();
                        $filePath = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $videoModel->fine_uploader_uuid . DIRECTORY_SEPARATOR . $newname);
                        $keyFile = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'enc.keyinfo');
                        $keyFilePath = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'enc.key');
                        $m3u8FilePath = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'playlist.m3u8');
                        $desinitionFile = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'convert' . DIRECTORY_SEPARATOR . $videoModel->fine_uploader_uuid . DIRECTORY_SEPARATOR);
                        if (!is_dir($desinitionFile)) {
                            @mkdir($desinitionFile, 0777, true);
                        }
                        $content = "enc.key\r\n" . $desinitionFile . "/enc.key\r\nad62700d4e74263579281a3762f8b724";
                        file_put_contents($keyFile, $content, LOCK_EX);
                        try {
                            \File::copy($keyFilePath, $desinitionFile . 'enc.key');
                            \File::copy($m3u8FilePath, $desinitionFile . '/playlist.m3u8');
                        } catch (\Exception  $e) {
                            $videoModel->job_status = 'Error';
                            $videoModel->save();
                            $ffmpegStatus = Ffmpegstatus::get()->first();
                            $ffmpegStatus->status = 1;
                            $ffmpegStatus->save();
                        }
                        $comment = 'nice ffmpeg -i ' . $filePath . ' -profile:v main -s 1920x1080 -q:v 1 -hls_list_size 0 -strict -2 -hls_time 5 -hls_key_info_file ' . $keyFile . ' -hls_segment_filename "' . $desinitionFile . '1920x1080fileSequences%d.ts"   ' . $desinitionFile . '1920x1080uploadprog_indexes.m3u8 -profile:v main -s 640x360 -q:v 1 -hls_list_size 0 -strict -2 -hls_time 5 -hls_key_info_file ' . $keyFile . ' -hls_segment_filename "' . $desinitionFile . '640x360fileSequences%d.ts"   ' . $desinitionFile . '640x360uploadprog_indexes.m3u8 -profile:v main -s 1280x720 -q:v 1 -hls_list_size 0 -strict -2 -hls_time 5 -hls_key_info_file ' . $keyFile . ' -hls_segment_filename "' . $desinitionFile . '1280x720fileSequences%d.ts"   ' . $desinitionFile . '1280x720uploadprog_indexes.m3u8';
                        $videoModel->job_status = 'Progressing';
                        $videoModel->save();
                        $output = shell_exec($comment . "  2>&1; echo $?");
                        $randomFileDir = rand(5, 15) . date('m-d-Y_hia');
                        if ($output) {
                            $source = rtrim(trim($desinitionFile), "/");
                            if ($handle = opendir($source)) {
                                while (false !== ($file = readdir($handle))) {
                                    if ($file !== "." && $file !== ".." && $file !== ".DS_Store") {
                                        try {
                                            $this->awsRepository->uploadConvertedFiles($source, $file, $randomFileDir, $newname);
                                            $region = config()->get('settings.aws-settings.aws-general.aws_region');
                                            $awsS3Bucket = config()->get('settings.aws-settings.aws-general.aws_s3_bucket');
                                            $videoNewURL = 'https://s3.' . $region . '.amazonaws.com/' . $awsS3Bucket . '/FFMPEG/' . $randomFileDir . '/playlist.m3u8';
                                            $videoModel->hls_playlist_url = $videoNewURL;
                                            $videoModel->aws_prefix = 'FFMPEG/' . $randomFileDir;
                                            $videoModel->save();
                                        } catch (Exception $ex) {
                                            $videoModel->job_status = 'Error';
                                            $videoModel->save();
                                            $ffmpegStatus = Ffmpegstatus::get()->first();
                                            $ffmpegStatus->status = 1;
                                            $ffmpegStatus->save();
                                            echo $ex->getMessage();
                                            exit;
                                        }
                                    }
                                }
                                closedir($handle);
                                $videoModel->job_status = 'Complete';
                                $videoModel->is_active = 1;
                                $videoModel->save();
                                $ffmpegStatus = Ffmpegstatus::get()->first();
                                $ffmpegStatus->status = 1;
                                $ffmpegStatus->save();

                                if (file_exists($desinitionFile)) {
                                    shell_exec("sudo rm -rf " . $desinitionFile);
                                }
                            }
                        } else {
                            $videoModel->job_status = 'Error';
                            $videoModel->save();
                            $ffmpegStatus = Ffmpegstatus::get()->first();
                            $ffmpegStatus->status = 1;
                            $ffmpegStatus->save();
                        }
                        exit;
                    }
                }
            } else {
                $unfinishedJobs = Video::where('job_status', '!=', 'Complete')->where('job_status', '!=', 'Uploading')->where('job_id', '!=', '')->get();
                foreach ($unfinishedJobs as $unfinishedJob) {
                    try {
                        $client = ElasticTranscoderClient::factory(array('region' => config()->get('settings.aws-settings.aws-general.aws_region'), 'version' => config('contus.video.video.aws_sdk_version'), 'credentials' => ['key' => config()->get('settings.aws-settings.aws-general.aws_key'), 'secret' => config()->get('settings.aws-settings.aws-general.aws_secret')]));
                        $result = $client->readJob(array('Id' => $unfinishedJob ['job_id']));
                        if ($result ['Job']) {
                            $jobStatus = $result ['Job'] ['Status'];
                            /**
                             * Update job status in the database.
                             */
                            $this->video = new Video ();
                            $videoInstance = $this->video->findorfail($unfinishedJob ['id']);
                            $videoInstance->job_status = $jobStatus;
                            $videoInstance->is_active = 1;
                            $videoInstance->save();

                            /**
                             * Delete the fine uploader file in the server if the job status is Complete.
                             */
                            if ($jobStatus == "Complete") {
                                $s3Client = S3Client::factory(array('region' => config()->get('settings.aws-settings.aws-general.aws_region'), 'version' => config('contus.video.video.aws_sdk_version'), 'credentials' => ['key' => config()->get('settings.aws-settings.aws-general.aws_key'), 'secret' => config()->get('settings.aws-settings.aws-general.aws_secret')]));
                                $awsS3Bucket = config()->get('settings.aws-settings.aws-general.aws_s3_bucket');
                                $filePath = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $videoInstance->fine_uploader_uuid . DIRECTORY_SEPARATOR . $videoInstance->fine_uploader_name);
                                $folderPath = base_path('public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $videoInstance->fine_uploader_uuid);
                                if (file_exists($filePath)) {
                                    unlink($filePath);
                                }
                                if (file_exists($folderPath)) {
                                    rmdir($folderPath);
                                }
                            }
                        }
                    } catch (\Exception $exception) {
                        app('log')->error(' ###File : ' . $exception->getFile() . ' ##Line : ' . $exception->getLine() . ' #Error : ' . $exception->getMessage());
                    }

                }
            }
        };
    }

    /**
     * Function to save the thumburl if hls
     *
     * @param array $objects
     * @param string $awsRegion
     * @param string $awsS3Bucket
     * @param array $videoInstance
     */
    public function save_thumb_hls($objects, $awsRegion, $awsS3Bucket, $videoInstance)
    {
        foreach ($objects ['Contents'] as $thumb) {
            $transcodedThumb = new TranscodedVideo ();
            $transcodedThumb->video_id = $videoInstance->id;
            $transcodedThumb->thumb_url = 'https://s3.' . $awsRegion . '.amazonaws.com/' . $awsS3Bucket . '/' . $thumb ['Key'];
            $transcodedThumb->is_active = 1;
            $transcodedThumb->save();
        }
    }
}
