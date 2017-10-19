<?php

/**
 * Video Repository
 *
 * To manage the functionalities related to videos
 *
 * @name VideoRepository
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http: www.gnu.org/copyleft/gpl.html
 *
 */

namespace Contus\Video\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Video\Contracts\IVideoRepository;
use Contus\Base\Repositories\UploadRepository;
use Contus\Video\Models\Video;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Video\Repositories\VideoCountriesRepository;
use Contus\Video\Repositories\VideoCastRepository;
use Contus\Video\Repositories\VideoLikesRepository;
use Contus\Video\Repositories\WatchLatersRepository;
use Contus\Video\Repositories\QuestionsRepository;
use Contus\Video\Models\Category;
use Contus\Video\Models\Tag;
use Contus\Video\Models\VideoTag;
use Contus\Video\Models\Collection;
use Contus\Video\Models\VideoPreset;
use Carbon\Carbon;
use Contus\Base\Helpers\StringLiterals;
use Contus\Video\Models\VideoCategory;
use Contus\Video\Models\VideoPoster;
use Contus\Video\Models\VideoCast;
use Contus\Notification\Traits\NotificationTrait;
use Contus\Video\Traits\CollectionTrait;
use Contus\Video\Models\Group;
use Illuminate\Support\Facades\Cache;

class VideoRepository extends BaseRepository implements IVideoRepository
{

    use NotificationTrait,CollectionTrait;
    /**
     * class property to hold the instance of Video Model
     *
     * @var \Contus\Video\Models\Video
     */
    public $video;
    /**
     * class property to hold the instance of AWSUploadRepository
     *
     * @var \Contus\Video\Repositories\AWSUploadRepository
     */
    public $awsRepository;
    /**
     * Class property to hold the key which hold the group name requested
     *
     * @var string
     */
    protected $requestedCollection = 'q';
    /**
     * class property to hold the instance of UploadRepository
     *
     * @var \Contus\Base\Repositories\UploadRepository
     */
    public $uploadRepository;

    /**
     * Construct method initialization
     *
     * Validation rule for user verification code and forgot password.
     */
    public function __construct(AWSUploadRepository $awsRepository, UploadRepository $uploadRepository, VideoCountriesRepository $videoCountriesRepository, VideoCastRepository $videoCastRepository, CommentsRepository $commentRepository, QuestionsRepository $questionrepository,VideoLikesRepository $videolikeRepository,WatchLatersRepository $watchlaterRepository)
    {
        parent::__construct();

        /**
         * Set other class objects to properties of this class.
         */
        $this->video = new Video ();
        $this->category = new Category ();
        $this->videoPreset = new VideoPreset ();
        $this->tag = new Tag ();
        $this->videoTag = new VideoTag ();
        $this->videoCategory = new VideoCategory ();
        $this->awsRepository = $awsRepository;
        $this->uploadRepository = $uploadRepository;
        $this->videoCountriesRepository = $videoCountriesRepository;
        $this->videoCastRepository = $videoCastRepository;
        $this->commentRepository = $commentRepository;
        $this->questionRepository = $questionrepository;
        $this->videolikeRepository = $videolikeRepository;
        $this->watchlaterRepository = $watchlaterRepository;
        $this->categoryRepository = new CategoryRepository (new Category (), new UploadRepository ());

        $this->setRules([StringLiterals::TITLE => StringLiterals::REQUIRED, 'video_url' => StringLiterals::REQUIRED, 'is_featured_time' => StringLiterals::REQUIRED]);
    }

    /**
     * Function to add a video.
     *
     * @return boolean integer id if video is added successfully and False if not.
     */
    public function addVideo()
    {
        $typeId = null;
        $videoDetails = $this->request->video_details;
        $video = new Video ();
        $video->creator_id = $this->authUser->id;
        $video->title = $videoDetails ['name'];
        $video->job_status = 'Video Uploaded';
        $video->is_featured = 0;
        $video->is_active = 0;
        $video->updator_id = $this->authUser->id;
        $video->fine_uploader_uuid = $videoDetails ['uuid'];
        $video->fine_uploader_name = $videoDetails ['name'];

        /**
         * Save the video in the database.
         */
        if ($video->save()) {
            $typeId = $video->id;
            /**
             * Associate the newly added video with uncategorized category.
             */
            $return = $typeId;
        } else {
            $return = $typeId;
        }
        return $return;
    }

    /**
     * Function to update a video.
     *
     * @param integer $id
     * The id of the video.
     * @return boolean True if video updated successfully and False if not.
     */
    public function updateVideo($id)
    {
        /**
         * Check if the video id is not empty.
         */
        if (!empty ($id)) {
            /**
             * Set validation rules for edit functionality.
             */
            $video = $this->video->findorfail($id);
            if ($video->youtube_live) {
                $this->setRules([StringLiterals::TITLE => StringLiterals::REQUIRED, 'presenter' => 'required', 'short_description' => StringLiterals::REQUIRED, 'is_featured' => StringLiterals::REQUIREDINTEGER, StringLiterals::ISACTIVE => StringLiterals::REQUIREDINTEGER]);
            } else {
                $this->setRules([StringLiterals::TITLE => StringLiterals::REQUIRED, 'presenter' => 'required', StringLiterals::CATEGORYIDS => 'required|array', 'short_description' => StringLiterals::REQUIRED, 'is_featured' => StringLiterals::REQUIREDINTEGER, StringLiterals::ISACTIVE => StringLiterals::REQUIREDINTEGER, 'trailer_status' => 'required']);
            }
            $this->validate($this->request, $this->getRules());
            $video->title = $this->request->title;
            $video->short_description = $this->request->short_description;
            $video->description = $this->request->description;
            $video->trailer_status = ( int )$this->request->trailer_status;
            $video->is_active = ( int )$this->request->is_active;
            $video->published_on = Carbon::parse($this->request->published_on);
            $video->updator_id = $this->authUser->id;
            $video->video_order = ( int )$this->request->video_order;
            if ($this->request->has('presenter')) {
                $video->presenter = $this->request->presenter;
            }
            if ($this->request->has('tags')) {
                $this->videoTag->where(StringLiterals::VIDEOID, $id)->delete();
                foreach ($this->request->tags as $value) {
                    $tag = $this->tag->where('name', $value)->first();
                    if (empty ($tag)) {
                        $tag = new Tag ();
                        $tag->name = $value;
                        $tag->save();
                    }
                    $tag->videos()->attach($id);
                }
            }
            if ($this->request->has('mp3')) {
                $mp3Url = explode("/", $this->request->mp3);
                $video->mp3 = 'mp3/' . $mp3Url [count($mp3Url) - 1];
            }
            if ($this->request->has(StringLiterals::THUMBNAIL)) {
                $thumbUrl = explode("/", $this->request->thumbnail);
                $video->thumbnail_image = $thumbUrl [count($thumbUrl) - 1];
                $video->thumbnail_path = $thumbUrl [count($thumbUrl) - 1];
            }
            $isVideo = false;
            $video = $this->updateImage($video);
            /**
             * Update the video details in the data base.
             */
            if ($video->save()) {
                $this->saveVideoCategories($id);
                $isVideo = true;
            }
            return $isVideo;
        } else {
            return false;
        }
    }

    /**
     * Function to update image
     *
     * @param object $video
     * @return object
     */
    private function updateImage($video)
    {
        if ($this->request->has('selected_thumb')) {
            if ($this->request->selected_thumb == 'thumbnail_image') {
                if (!empty ($video->thumbnail_image)) {
                    $video->selected_thumb = $video->thumbnail_image;
                }
            } else if (!empty ($this->request->selected_thumb) && $this->request->selected_thumb !== $video->selected_thumb) {
                $video->selected_thumb = $this->request->selected_thumb;
            }
        }
        if ($this->request->has('pdf')) {
            $imageUrl = explode('/', $this->request->pdf);
            if ($imageUrl [0] != 'http:' && $imageUrl [0] != 'https:') {
                $video->pdf = $this->request->pdf;
            }
        }
        if ($this->request->has('word')) {
            $imageUrl = explode('/', $this->request->word);
            if ($imageUrl [0] != 'http:' && $imageUrl [0] != 'https:') {
                $video->word = $this->request->word;
            }
        }
        return $video;
    }

    /**
     * Function to save categories of a video in the database.
     *
     * @param integer $id
     * The id of the video whose categories are being saved.
     */
    public function saveVideoCategories($id)
    {
        $this->videoCategory = new VideoCategory ();
        $this->videoCategory->where(StringLiterals::VIDEOID, $id)->delete();
        if ($this->request->has(StringLiterals::CATEGORYIDS) && is_array($this->request->input(StringLiterals::CATEGORYIDS)) && count($this->request->input(StringLiterals::CATEGORYIDS)) > 0) {
            foreach ($this->request->input(StringLiterals::CATEGORYIDS) as $categoryId) {
                $this->videoCategory = new VideoCategory ();
                $this->videoCategory->video_id = $id;
                $this->videoCategory->category_id = $categoryId;
                $this->videoCategory->save();
                $video = Video::find($id);
                $categoryy = Category::where('id', $categoryId)->first();
                Cache::forget('relatedCategoryList' . $categoryy->slug);
                $video->collections()->detach();
            }
        }
        if ($this->request->has('exam_ids') && is_array($this->request->input('exam_ids')) && count($this->request->input('exam_ids')) > 0) {
            $video->collections()->attach(Group::whereIn('id', $this->request->exam_ids)->pluck('id')->toArray());
            $groups = Group::whereIn('id', $this->request->exam_ids)->get();
            foreach ($groups as $group) {
                Cache::forget('groupList' . $group->slug);
            }
        }
        $videoClearCache = Video::where('id', $this->request->id);
        if ($videoClearCache->has('playlists')->first() ['id']) {
            $play = $videoClearCache->first()->playlists();
            $cslug = $play->get();
            foreach ($cslug as $playlist) {
                Cache::forget('playlistList' . $playlist->slug);
            }
        }
    }

    /**
     * Function to get validation rules for video edit form.
     *
     * @return array The validation rules.
     */
    public function getVideoEditRules()
    {
        /**
         * Set rules for video edit feature.
         */
        $this->setRules([StringLiterals::TITLE => StringLiterals::REQUIRED, StringLiterals::CATEGORYID => StringLiterals::REQUIREDINTEGER, 'short_description' => StringLiterals::REQUIRED, 'is_featured' => StringLiterals::REQUIREDINTEGER, StringLiterals::ISACTIVE => StringLiterals::REQUIREDINTEGER, StringLiterals::TRAILER => 'url', 'presenter' => 'required']);

        return $this->getRules();
    }

    /**
     * Function to get validation rules for video thumb upload form.
     *
     * @return array The validation rules.
     */
    public function getThumbUploadRules()
    {
        /**
         * Set rules for thumbnail upload feature.
         */
        $this->setRules([StringLiterals::THUMBNAIL => StringLiterals::REQUIRED]);
        return $this->getRules();
    }

    /**
     * Function to update thumbnail of a video.
     *
     * @param integer $id
     * The id of the video
     * @return boolean True if uploaded successfully and false if not.
     */
    public function updateThumbnail($id)
    {
        /**
         * Check if the video id for the thumbnail is not empty.
         */
        if (!empty ($id)) {
            /**
             * Set the validation rules for the thumbnail.
             */
            $this->setRules([StringLiterals::THUMBNAIL => StringLiterals::REQUIRED]);
            /**
             * Perform validation for the thumbnail upload.
             */
            $this->validate($this->request, $this->getRules());

            $this->uploadRepository->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_THUMBNAIL)->setRequestParamKey(StringLiterals::THUMBNAIL)->setConfig();

            $video = $this->video->findorfail($id);
            if ($this->request->has(StringLiterals::THUMBNAIL)) {
                /**
                 * Upload the thumbnail.
                 */
                $this->uploadRepository->handleUpload($video);
                $isVideo = true;
            } else {
                $isVideo = false;
            }
            return $isVideo;
        } else {
            return false;
        }
    }

    /**
     * Function to archive videos in the database.
     * This function works like a soft delete and the video files in AWS S3 are not deleted.
     *
     * @param integer|array $ids
     * The ids of the videos which are to be deleted.
     * @return boolean True if the videos are archived successfully and false if not.
     */
    public function videoDelete($ids)
    {
        /**
         * Delete the video by the given id
         */
        $ids = is_array($ids) ? $ids : [$ids];
        return empty ($ids) ? StringLiterals::LITERALFALSE : $this->video->whereIn('id', $ids)->update([StringLiterals::IS_ARCHIVED => 1, 'archived_on' => Carbon::now()]);
    }

    /**
     * Function to activate the videos
     *
     * @param integer|array $ids
     * The ids of the videos which are to be activated.
     * @return boolean True if the videos are archived successfully and false if not.
     */
    public function videoActivateOrDeactivate($ids, $isStatus)
    {
        /**
         * Delete the video by the given id
         */
        $ids = is_array($ids) ? $ids : [$ids];
        /**
         * Check if the status is activate.
         * If yes, set is_active field to 1.
         * If no, then set is_active field to 0.
         */
        if ($isStatus == 'activate') {
            return empty ($ids) ? StringLiterals::LITERALFALSE : $this->video->whereIn('id', $ids)->update([StringLiterals::ISACTIVE => 1]);
        } else if ($isStatus == 'deactivate') {
            return empty ($ids) ? StringLiterals::LITERALFALSE : $this->video->whereIn('id', $ids)->update([StringLiterals::ISACTIVE => 0]);
        }
    }

    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     *
     * @vendor Contus
     *
     * @package Collection
     * @return Contus\Collection\Repositories\BaseRepository
     */
    public function prepareGrid()
    {
        /**
         * To load the data in to the grid depands up on request
         */
        $this->setGridModel($this->video)->setEagerLoadingModels(['videocategory.category', 'collections']);
        return $this;
    }

    /**
     * Get headings for grid
     *
     * @vendor Contus
     *
     * @package Collection
     * @return array
     */
    public function getGridHeadings()
    {
        $filters = $this->request->input('filters');
        $checkLive = false;
        if (!empty ($filters)) {
            foreach ($filters as $value) {
                if ($value == 'live_videos') {
                    $checkLive = true;
                }
            }
        }
        if ($checkLive) {
            return [StringLiterals::GRIDHEADING => [['name' => trans('video::videos.title'), StringLiterals::VALUE => StringLiterals::TITLE, 'sort' => true], ['name' => trans('video::videos.status'), StringLiterals::VALUE => StringLiterals::ISACTIVE, 'sort' => false], ['name' => 'Type', StringLiterals::VALUE => StringLiterals::ISACTIVE, 'sort' => false], ['name' => trans('video::videos.status'), StringLiterals::VALUE => 'liveStatus', 'sort' => false], ['name' => trans('video::videos.scheduled_on'), StringLiterals::VALUE => '', 'sort' => false], ['name' => trans('video::videos.added_on'), StringLiterals::VALUE => '', 'sort' => false], ['name' => trans('video::videos.action'), StringLiterals::VALUE => '', 'sort' => false]]];
        } else {
            return [StringLiterals::GRIDHEADING => [['name' => trans('video::videos.title'), StringLiterals::VALUE => StringLiterals::TITLE, 'sort' => true], ['name' => trans('video::videos.sections'), StringLiterals::VALUE => StringLiterals::CATEGORYID, 'sort' => false], ['name' => trans('video::videos.exams_groups'), StringLiterals::VALUE => '', 'sort' => false], ['name' => trans('video::videos.status'), StringLiterals::VALUE => StringLiterals::ISACTIVE, 'sort' => false], ['name' => trans('video::videos.upload_status'), StringLiterals::VALUE => 'job_status', 'sort' => false], ['name' => trans('video::videos.added_on'), StringLiterals::VALUE => '', 'sort' => false], ['name' => trans('video::videos.action'), StringLiterals::VALUE => '', 'sort' => false]]];
        }
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($builder)
    {

        /**
         * updated the grid query by using this function and apply the video condition.
         */
        $filters = $this->request->input('filters');

        if (!empty ($filters)) {
            foreach ($filters as $value) {
                switch ($value) {
                    case "normal_videos" :
                        $builder->where(StringLiterals::IS_ARCHIVED, 0)->where('youtube_live', 0);
                        break;
                    case "live_videos" :
                        $builder->where(StringLiterals::IS_ARCHIVED, 0)->where('youtube_live', 1);
                        break;
                    default :
                        $builder->where(StringLiterals::IS_ARCHIVED, 0)->where('youtube_live', 0);
                        break;
                }
            }
        }
        if (!$filters) {
            return $builder->where('youtube_live', 0)->where(StringLiterals::IS_ARCHIVED, 0);
        } else {
            return $builder;
        }
    }

    /**
     * Function to apply filter for search of videos grid
     *
     * @param mixed $builderVideos
     * @return \Illuminate\Database\Eloquent\Builder $builderVideos The builder object of videos grid.
     */
    protected function searchFilter($builderVideos)
    {
        $searchRecordVideos = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        $title = $is_active = $type = null;
        extract($searchRecordVideos);
        /**
         * Check if the title of the video is present in the video search.
         * If yes, then use it in filter.
         */
        if ($title) {
            $builderVideos = $builderVideos->where(StringLiterals::TITLE, 'like', '%' . $title . '%');
        }
        /**
         * Check if the status of the video is present in the video search.
         * If yes, then use it in filter.
         */
        if (is_numeric($is_active)) {
            $builderVideos = $builderVideos->where(StringLiterals::ISACTIVE, $is_active);
        }
        /**
         * Check if the type of the video is present in the video search.
         * If yes, then use it in filter.
         */
        if ($type == "wowza") {
            $builderVideos = $builderVideos->where(StringLiterals::USERNAME, $type);
        } else if ($type != null && $type != 'all') {
            $builderVideos = $builderVideos->where(StringLiterals::YOUTUBE_PRIVACY, $type);
        }

        return $builderVideos;
    }

    /**
     * Fetch video to edit.
     *
     * @vendor Contus
     *
     * @package Video
     * @return response
     */
    public function getVideo($id)
    {
        return $this->video->with('videocategory.category.videos.transcodedvideos', 'transcodedvideos.presets', 'tags', 'collections')->where('id', $id)->where(StringLiterals::IS_ARCHIVED, 0)->first();
    }

    /**
     * Function to fetch all the details of a video from the database.
     *
     * @param integer $id
     * The id of the video whose data are to be fetched.
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|NULL The information of the video.
     */
    public function getCompleteVideoDetails($id)
    {
        $this->video = $this->video->with(['tags', 'categories.parent_category.parent_category', 'collections'])->where('id', $id)->where(StringLiterals::IS_ARCHIVED, 0)->first();
        $this->video->recent = $this->video->recent()->get()->count();
        $this->video->authfavourites = $this->video->authfavourites()->get()->count();
        return $this->video;
    }

    /**
     * Repository function to delete poster of a video.
     *
     * @param integer $id
     * The id of the poster.
     * @return boolean True if the poster is deleted and false if not.
     */
    public function deletePoster($id)
    {
        /**
         * Check if poster id exists.
         */
        if (!empty ($id)) {
            $videoPoster = VideoPoster::findorfail($id);
            /**
             * Delete the poster image using the image path field from the database.
             */
            if (file_exists($videoPoster->image_path) && unlink($videoPoster->image_path)) {
                /**
                 * Delete the poster in the database.
                 */
                $videoPoster->delete();
                $deleteStatus = true;
            } else {
                $deleteStatus = false;
            }
            return $deleteStatus;
        } else {
            return false;
        }
    }

    /**
     * Repository function to delete cast image of a video.
     *
     * @param integer $id
     * The id of the cast image.
     * @return boolean True if the cast image is deleted and false if not.
     */
    public function deleteCastImage($id)
    {
        /**
         * Check if cast id exists.
         */
        if (!empty ($id)) {
            $videoCast = VideoCast::findorfail($id);
            /**
             * Delete the cast image using the image path field from the database.
             */
            if (file_exists($videoCast->image_path) && unlink($videoCast->image_path)) {
                /**
                 * Delete the cast image in the database.
                 */
                $videoCast->image_url = null;
                $videoCast->image_path = null;
                $videoCast->save();
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
