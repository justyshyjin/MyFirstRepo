<?php

/**
 * Video Controller
 *
 * To manage the Video such as create, edit and delete
 *
 * @name Video Controller
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Video\Api\Controllers\Admin;

use Illuminate\Http\Request;
use Contus\Video\Repositories\VideoRepository;
use Contus\Video\Repositories\CategoryRepository;
use Contus\Video\Repositories\CollectionRepository;
use Contus\Video\Repositories\CountriesRepository;
use Contus\Video\Repositories\VideoCountriesRepository;
use Contus\Base\Repositories\UploadRepository;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Contus\Video\Helpers\UploadHandler;
use Contus\Video\Repositories\PresetRepository;
use Contus\Video\Repositories\PlaylistRepository;
use Contus\Video\Models\Playlist;
use Contus\Customer\Models\MypreferencesVideo;
use Contus\Notification\Traits\NotificationTrait;
use Illuminate\Support\Facades\File as Makefile;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Video\Models\TranscodedVideo;
use Contus\Video\Models\VideoPreset;
use Contus\Video\Models\LiveScheduler;
use Contus\Video\Traits\CollectionTrait;

class VideoController extends ApiController
{
    use NotificationTrait,CollectionTrait;
    public $collectionsRepository;
    /**
     * class property to hold the instance of UploadRepository
     *
     * @var \Contus\Base\Repositories\UploadRepository
     */
    public $uploadRepository;

    /**
     * Constructer method which defines the objects of the classes used.
     *
     * @param object $videosRepository
     * The object of VideoRepository class
     */
    public function __construct(VideoRepository $videosRepository, CollectionRepository $collectionsRepository, CategoryRepository $categoryRepository, UploadRepository $uploadRepository, CountriesRepository $countriesRepository, VideoCountriesRepository $videoCountriesRepository)
    {
        parent::__construct();

        $this->repository = $videosRepository;
        $this->collectionsRepository = $collectionsRepository;
        $this->categoryRepository = $categoryRepository;
        $this->uploadRepository = $uploadRepository;
        $this->countriesRepository = $countriesRepository;
        $this->videoCountriesRepository = $videoCountriesRepository;
        $this->presetRepository = new PresetRepository ();
        $this->playlistsRepository = new PlaylistRepository (new Playlist (), new MypreferencesVideo ());
        $this->repository->setRequestType(static::REQUEST_TYPE);
        $this->awsRepository = new AWSUploadRepository (new TranscodedVideo (), new VideoPreset ());
    }

    /**
     * Function to save a new video.
     *
     * @return \Illuminate\Http\Response
     */
    public function postAdd()
    {
        $isCreated = false;
        /**
         * Call addVideo repository function to add video to the database.
         */
        $videoId = $this->repository->addVideo();
        if ($videoId != null) {
            $isCreated = true;
            $this->request->session()->flash(StringLiterals::SUCCESS, trans('video::videos.message.success'));
        }
        /**
         * If the video is added successfully, return the success response.
         * If the video is not added successfully, then return the error response.
         */
        return ($isCreated) ? $this->getSuccessJsonResponse([$videoId, StringLiterals::MESSAGE => trans('video::videos.message.success')]) : $this->getErrorJsonResponse([], trans('video::videos.message.error'));
    }

    /**
     * Function to update a video
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function postEdit($id)
    {
        $isUpdated = false;

        try {
            /**
             * Call updateVideo repository method to update details of a video.
             */
            if ($this->repository->updateVideo($id)) {
                $isUpdated = true;
                $this->request->session()->flash(StringLiterals::SUCCESS, trans('video::videos.message.update-success'));
            }
        } catch (Exception $e) {
            /**
             * Handle the error exception if the requested video does not exist.
             */
            $this->request->session()->flash(StringLiterals::ERROR, trans('video::videos.resource_not_exist'));
            $isUpdated = true;
        }
        /**
         * If the video is updated successfully, then return the success response.
         * If the video is not updated successfull, then return the error response.
         */
        return ($isUpdated) ? $this->getSuccessJsonResponse([StringLiterals::MESSAGE => trans('video::videos.message.update-success')]) : $this->getErrorJsonResponse([], trans('video::videos.message.update-error'));
    }

    /**
     * Function to upload thumbnail image for a video.
     *
     * @param integer $id
     * The id of the video.
     * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
     */
    public function postUploadThumbnail($id)
    {
        $isThumbnailUpdated = false;

        try {
            /**
             * Call the updateThumbnail repository method to update a video thumbnail.
             */
            if ($this->repository->updateThumbnail($id)) {
                $isThumbnailUpdated = true;
                $this->request->session()->flash(StringLiterals::SUCCESS, trans('video::videos.message.thumbnail-success'));
            }
        } catch (Exception $e) {
            /**
             * Handle the exception when the requested resource does not exist.
             */
            $this->request->session()->flash(StringLiterals::ERROR, trans(StringLiterals::VIDEO_NOT_EXIST));
            $isThumbnailUpdated = true;
        }

        /**
         * If the thumbnail is updated successfully, then return the success response.
         * If the thumbnail is not updated successfully, then return the error response.
         */
        return ($isThumbnailUpdated) ? $this->getSuccessJsonResponse([StringLiterals::MESSAGE => trans('video::videos.message.thumbnail-success')]) : $this->getErrorJsonResponse([], trans('video::videos.message.thumbnail-error'));
    }

    /**
     * Controller function to delete custom thumbnail of a video.
     *
     * @param integer $id
     * The id of the video.
     * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
     */
    public function postDeleteThumbnail($id)
    {
        $isThumbnailDeleted = false;

        try {
            /**
             * Call the deleteThumbnail repository method to delete thumbnail of a video.
             */
            if ($this->repository->deleteThumbnail($id)) {
                $isThumbnailDeleted = true;
                $this->request->session()->flash(StringLiterals::SUCCESS, trans('video::videos.message.thumbnail-delete-success'));
            }
        } catch (Exception $e) {
            /**
             * Handle the error exception when the video of the thumbnail does not exist.
             */
            $this->request->session()->flash(StringLiterals::ERROR, trans(StringLiterals::VIDEO_NOT_EXIST));
            $isThumbnailDeleted = true;
        }
        /**
         * If the thumbnail is deleted successfully, return the success response.
         * If the thumbnail is not deleted successfully, return the failure resposne.
         */
        return ($isThumbnailDeleted) ? $this->getSuccessJsonResponse([StringLiterals::MESSAGE => trans('video::videos.message.thumbnail-delete-success')]) : $this->getErrorJsonResponse([], trans('video::videos.message.thumbnail-delete-error'));
    }

    /**
     * Controller function to delete subtitle of a video.
     *
     * @param integer $id
     * The id of the video.
     * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
     */
    public function postDeleteSubtitle($id)
    {
        $isSubtitleDeleted = false;

        try {
            /**
             * Call the deleteSubtitle repository method to delete subtitle of a video.
             */
            if ($this->repository->deleteSubtitle($id)) {
                $isSubtitleDeleted = true;
                $this->request->session()->flash(StringLiterals::SUCCESS, trans('video::videos.message.subtitle-delete-success'));
            }
        } catch (Exception $e) {
            /**
             * Handle the error exception when the video of the subtitle does not exist.
             */
            $this->request->session()->flash(StringLiterals::ERROR, trans(StringLiterals::VIDEO_NOT_EXIST));
            $isSubtitleDeleted = true;
        }
        /**
         * If the subtitle is deleted successfully, return the success response.
         * If the subtitle is not deleted successfully, return the failure resposne.
         */
        return ($isSubtitleDeleted) ? $this->getSuccessJsonResponse([StringLiterals::MESSAGE => trans('video::videos.message.subtitle-delete-success')]) : $this->getErrorJsonResponse([], trans('video::videos.message.thumbnail-delete-error'));
    }

    /**
     * get Information for create form
     * return various information request by the form
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo()
    {
        return $this->getSuccessJsonResponse(
            ['info' =>
                ['rules' => $this->repository->getRules(),
                    'livesyncdata' => LiveScheduler::get(),
                    'video_edit_rules' => $this->repository->getVideoEditRules(),
                    'thumb_upload_rules' => $this->repository->getThumbUploadRules(),
                    'collection_rules' => $this->collectionsRepository->getRules(),
                    'locale' => trans('validation'),
                    'isActive' => ['In-active', 'Active'],
                    'allPlaylist' => $this->playlistsRepository->getAllPlaylistList(),
                    'allCollection' => $this->collectionsRepository->getAllCollection(),
                    'allCategories' => $this->categoryRepository->getAllCategories(),
                    'numberOfActivePresets' => $this->presetRepository->getNumberOfActivePresets(),
                    'allCountries' => $this->countriesRepository->getAllCountries()
                ]
            ]);
    }

    /**
     * Function to archive videos in the database.
     *
     * @see \Contus\Base\ApiController::postAction()
     * @return \Illuminate\Http\Response
     */
    public function postDeleteAction()
    {
        if ($this->request->has(StringLiterals::SELECTED_CHECKBOX) && is_array($this->request->get(StringLiterals::SELECTED_CHECKBOX))) {
            $isActionCompleted = $this->repository->videoDelete($this->request->input(StringLiterals::SELECTED_CHECKBOX));
            if ($this->request->get('videoStatus') == 'single-video') {
                return $isActionCompleted ? $this->getSuccessJsonResponse([], trans('video::videos.message.delete-success')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            } else if ($this->request->get('videoStatus') == 'bulk-video') {
                return $isActionCompleted ? $this->getSuccessJsonResponse([], trans('video::videos.message.bulk-delete-success')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            }
        }
    }

    /**
     * Function to bulk activate or deactivate the videos in the database.
     *
     * @see \Contus\Base\ApiController::postAction()
     * @return \Illuminate\Http\Response
     */
    public function postBulkUpdateStatus()
    {
        if ($this->request->has(StringLiterals::SELECTED_CHECKBOX) && is_array($this->request->get(StringLiterals::SELECTED_CHECKBOX))) {
            if ($this->request->get('isStatus') == 'activate') {

                $isActionCompleted = $this->repository->videoActivateOrDeactivate($this->request->input(StringLiterals::SELECTED_CHECKBOX), 'activate');
                return $isActionCompleted ? $this->getSuccessJsonResponse([], trans('video::videos.message.bulk-activate')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            } else if ($this->request->get('isStatus') == 'deactivate') {
                $isActionCompleted = $this->repository->videoActivateOrDeactivate($this->request->input(StringLiterals::SELECTED_CHECKBOX), 'deactivate');
                return $isActionCompleted ? $this->getSuccessJsonResponse([], trans('video::videos.message.bulk-deactivate')) : $this->getErrorJsonResponse([], trans(StringLiterals::INVALID_REQUEST_TRANS), 403);
            }
        }
    }

    /**
     * Upload the thumbnail image
     *
     * @param string $modelIdentifier
     * @return Response
     */
    public function postThumbnail()
    {
        $tempImageInfo = $this->uploadRepository->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_THUMBNAIL)->tempPrepare()->tempUpload();
        return empty ($tempImageInfo) ? $this->getErrorJsonResponse([], trans(StringLiterals::UNABLE_TO_UPLOAD)) : $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }

    /**
     * Get all collection to update in to list
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getCollectionUpdate()
    {
        return $this->getSuccessJsonResponse(['info' => ['allCollection' => $this->collectionsRepository->getAllCollection()]]);
    }

    /**
     * get Detail video view and edit template
     *
     * @return \Illuminate\Http\View
     */
    public function getVideoToEdit($id)
    {
        $getVideo = $this->repository->getVideo($id);
        $getCountryIdByVideo = $this->videoCountriesRepository->getCountryIdByVideoId($id);
        return (is_null($getVideo)) ? $this->getErrorJsonResponse([], null, 404) : $this->getSuccessJsonResponse(['response' => $getVideo, 'country_id' => $getCountryIdByVideo]);
    }

    /**
     * Function to get complete video details of a video.
     *
     * @param integer $id
     * The id of the video whose details are to be fetched.
     * @return \Contus\Base\response A JSON string which contains all the information of the video.
     */
    public function getCompleteVideoDetails($id)
    {
        $videoDetails = $this->repository->getCompleteVideoDetails($id);
        return (is_null($videoDetails)) ? $this->getErrorJsonResponse([], null, 404) : $this->getSuccessJsonResponse(['response' => $videoDetails]);
    }

    /**
     * Upload the profile image
     *
     * @param string $modelIdentifier
     * @return Response
     */
    public function postSubtitle()
    {
        $subTitleInfo = $this->uploadRepository->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_SUBTITLE)->tempPrepare()->tempUpload();
        return empty ($subTitleInfo) ? $this->getErrorJsonResponse([], trans(StringLiterals::UNABLE_TO_UPLOAD)) : $this->getSuccessJsonResponse(['info' => array_shift($subTitleInfo)]);
    }

    /**
     * funtion to upload file to s3 from by uploading banners
     */
    public function postUplaodBannerVideo()
    {
        $chunksFolders = base_path(StringLiterals::HANDLERPUBLIC . DIRECTORY_SEPARATOR . StringLiterals::UPLOADS . DIRECTORY_SEPARATOR . StringLiterals::VIDEOS . DIRECTORY_SEPARATOR . 'chunks');
        $filesFolders = base_path(StringLiterals::HANDLERPUBLIC . DIRECTORY_SEPARATOR . StringLiterals::UPLOADS . DIRECTORY_SEPARATOR . StringLiterals::VIDEOS . DIRECTORY_SEPARATOR . 'files');
        if (!file_exists(base_path(StringLiterals::HANDLERPUBLIC . DIRECTORY_SEPARATOR . StringLiterals::UPLOADS . DIRECTORY_SEPARATOR . StringLiterals::VIDEOS))) {
            Makefile::makeDirectory(base_path(StringLiterals::HANDLERPUBLIC . DIRECTORY_SEPARATOR . StringLiterals::UPLOADS . DIRECTORY_SEPARATOR . StringLiterals::VIDEOS), 0777, true, true);
        }
        if (!file_exists($filesFolders)) {
            Makefile::makeDirectory($filesFolders, 0777, true, true);
        }
        if (!file_exists($chunksFolders)) {
            Makefile::makeDirectory($chunksFolders, 0777, true, true);
        }
        $uploaders = new UploadHandler ();
        $uploaders->allowedExtensions = array();
        $uploaders->sizeLimit = null;
        $uploaders->inputName = "qqfile";
        $uploaders->chunksFolder = $chunksFolders;
        $method = $_SERVER ["REQUEST_METHOD"];
        if ($method == "POST") {
            header("Content-Type: text/plain");
            if (isset ($_GET ["done"])) {
                $result = $uploaders->combineChunks($filesFolders);
                $returnFilenames = $uploaders->getUploadName();
                $returnFilenames = $this->uploadRepository->generateFileName($uploaders->getUploadName());
                $returnFilenames = $this->awsRepository->uploadFileToS3($filesFolders . '/' . $result ["uuid"] . '/' . $uploaders->getUploadName(), $returnFilenames, 'images');
                $result ["uploadName"] = $returnFilenames;
            } else {
                $result = $uploaders->handleUpload($filesFolders);
                $returnFilename = $uploaders->getUploadName();
                if ($uploaders->getUploadName() !== null) {
                    $returnFilename = $this->uploadRepository->generateFileName($uploaders->getUploadName());
                    $returnFilename = $this->awsRepository->uploadFileToS3($filesFolders . '/' . $result ["uuid"] . '/' . $uploaders->getUploadName(), $returnFilename, 'images');
                }
                $result ["uploadName"] = $returnFilename;
            }
            if ($result ["uploadName"] !== null) {
                $result ["uploadName"] = explode("/", $result ["uploadName"]);
                $result ["uploadName"] = $result ["uploadName"] [count($result ["uploadName"]) - 2] . '/' . $result ["uploadName"] [count($result ["uploadName"]) - 1];
            }
            echo json_encode($result);
        } else if ($method == "DELETE") {
            $result = $uploaders->handleDelete($filesFolders);
            echo json_encode($result);
        } else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
    }

    /**
     * Function to handle file upload using fine uploader js.
     */
    public function postHandleFineUploader()
    {
        $chunksFolder = base_path(StringLiterals::HANDLERPUBLIC . DIRECTORY_SEPARATOR . StringLiterals::UPLOADS . DIRECTORY_SEPARATOR . StringLiterals::VIDEOS . DIRECTORY_SEPARATOR . 'chunks');
        $filesFolder = base_path(StringLiterals::HANDLERPUBLIC . DIRECTORY_SEPARATOR . StringLiterals::UPLOADS . DIRECTORY_SEPARATOR . StringLiterals::VIDEOS . DIRECTORY_SEPARATOR . 'files');
        if (!file_exists(base_path(StringLiterals::HANDLERPUBLIC . DIRECTORY_SEPARATOR . StringLiterals::UPLOADS . DIRECTORY_SEPARATOR . StringLiterals::VIDEOS))) {
            Makefile::makeDirectory(base_path(StringLiterals::HANDLERPUBLIC . DIRECTORY_SEPARATOR . StringLiterals::UPLOADS . DIRECTORY_SEPARATOR . StringLiterals::VIDEOS), 0777, true, true);
        }
        if (!file_exists($filesFolder)) {
            Makefile::makeDirectory($filesFolder, 0777, true, true);
        }
        if (!file_exists($chunksFolder)) {
            Makefile::makeDirectory($chunksFolder, 0777, true, true);
        }

        $uploader = new UploadHandler ();

        // Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
        // all files types allowed by default
        $uploader->allowedExtensions = array();

        // Specify max file size in bytes.
        $uploader->sizeLimit = null;

        // Specify the input name set in the javascript.
        // matches Fine Uploader's default inputName value by default
        $uploader->inputName = "qqfile";

        // If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
        $uploader->chunksFolder = $chunksFolder;

        $method = $_SERVER ["REQUEST_METHOD"];
        if ($method == "POST") {
            header("Content-Type: text/plain");

            // Assumes you have a chunking.success.endpoint set to point here with a query parameter of "done".
            // For example: /myserver/handlers/endpoint.php?done
            if (isset ($_GET ["done"])) {
                $result = $uploader->combineChunks($filesFolder);
            } else {
                // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
                $result = $uploader->handleUpload($filesFolder);
                $result ["uploadName"] = $uploader->getUploadName();
            }
            echo json_encode($result);
        } else if ($method == "DELETE") {
            $result = $uploader->handleDelete($filesFolder);
            echo json_encode($result);
        } else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
    }

    /**
     * Upload the poster images
     *
     * @param string $modelIdentifier
     * @return Response
     */
    public function postPosters()
    {
        $tempImageInfo = $this->uploadRepository->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_POSTER)->tempPrepare()->tempUpload();

        return empty ($tempImageInfo) ? $this->getErrorJsonResponse([], trans(StringLiterals::UNABLE_TO_UPLOAD)) : $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }

    /**
     * Upload the cast member images
     *
     * @param string $modelIdentifier
     * @return Response
     */
    public function postCastImages()
    {
        $tempImageInfo = $this->uploadRepository->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_CAST_IMAGE)->tempPrepare()->tempUpload();

        return empty ($tempImageInfo) ? $this->getErrorJsonResponse([], trans(StringLiterals::UNABLE_TO_UPLOAD)) : $this->getSuccessJsonResponse(['info' => array_shift($tempImageInfo)]);
    }

    /**
     * Controller function to delete a poster of a video.
     *
     * @param integer $id
     * The id of the poster to be deleted.
     * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
     */
    public function postDeletePoster($id)
    {
        $isPosterDeleted = false;

        try {
            /**
             * Call the deletePoster repository method to delete poster of a video.
             */
            if ($this->repository->deletePoster($id)) {
                $isPosterDeleted = true;
                $this->request->session()->flash(StringLiterals::SUCCESS, trans('video::videos.message.poster-delete-success'));
            }
        } catch (Exception $e) {
            /**
             * Handle the error exception when the poster of the video does not exist.
             */
            $this->request->session()->flash(StringLiterals::ERROR, trans('video::videos.poster_not_exist'));
            $isPosterDeleted = true;
        }
        /**
         * If the poster is deleted successfully, return the success response.
         * If the poster is not deleted successfully, return the failure resposne.
         */
        return ($isPosterDeleted) ? $this->getSuccessJsonResponse([StringLiterals::MESSAGE => trans('video::videos.message.poster-delete-success')]) : $this->getErrorJsonResponse([], trans('video::videos.message.poster-delete-error'));
    }

    /**
     * Controller function to delete a cast image of a video.
     *
     * @param integer $id
     * The id of the cast image to be deleted.
     * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
     */
    public function postDeleteCastImage($id)
    {
        $isCastImageDeleted = false;

        try {
            /**
             * Call the deleteCastImage repository method to delete cast image of a video.
             */
            if ($this->repository->deleteCastImage($id)) {
                $isCastImageDeleted = true;
                $this->request->session()->flash(StringLiterals::SUCCESS, trans('video::videos.message.cast-image-delete-success'));
            }
        } catch (Exception $e) {
            /**
             * Handle the error exception when the cast of the video does not exist.
             */
            $this->request->session()->flash(StringLiterals::ERROR, trans('video::videos.cast_not_exist'));
            $isCastImageDeleted = true;
        }
        /**
         * If the cast image is deleted successfully, return the success response.
         * If the cast image is not deleted successfully, return the failure resposne.
         */
        return ($isCastImageDeleted) ? $this->getSuccessJsonResponse([StringLiterals::MESSAGE => trans('video::videos.message.cast-image-delete-success')]) : $this->getErrorJsonResponse([], trans('video::videos.message.cast-image-delete-error'));
    }
}
