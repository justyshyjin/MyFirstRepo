<?php

/**
 * Video Controller
 *
 * To manage the Video such as create, edit and delete
 *
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 *
 *
 */
namespace Contus\Video\Api\Controllers\Frontend;

use Contus\Video\Repositories\FrontVideoRepository;
use Contus\Base\ApiController;
use Contus\Video\Repositories\CategoryRepository;
use Contus\Customer\Repositories\SubscriptionRepository;
use Contus\Cms\Repositories\TestimonialRepository;
use Contus\Video\Repositories\PlaylistRepository;
use Contus\Notification\Repositories\NotificationRepository;
use Contus\Customer\Repositories\FavouriteVideoRepository;
use Contus\Customer\Repositories\RecentlyViewedVideoRepository;
use Contus\Cms\Models\LatestNews;
use Contus\Customer\Repositories\CustomerRepository;
use Contus\Cms\Repositories\BannerRepository;
use Contus\Cms\Models\Banner;
use Contus\Notification\Models\Notification;
use Contus\Customer\Models\Customer;
use Contus\Video\Models\Video;
use Carbon\Carbon;
use Contus\Video\Models\Group;
use Contus\Video\Repositories\DashboardRepository;
use Contus\Video\Models\VideoPreset;
use Contus\Video\Models\Option;
use Contus\Video\Models\Category;
use Contus\Video\Models\Comment;
use Illuminate\Support\Facades\Cache;
use Aws\S3\S3Client;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Video\Models\TranscodedVideo;
use Contus\Customer\Models\SubscriptionPlan;
use Contus\Video\Traits\CollectionTrait as CollectionTrait;

class VideoController extends ApiController {
 use CollectionTrait;


    public $awsRepository;
    /**
     * constructor funtion for video controller
     *
     * @param FrontVideoRepository $videosRepository
     * @param CustomerRepository $customerrepositary
     * @param CategoryRepository $categoryRepository
     * @param SubscriptionRepository $subscriptionRepository
     * @param TestimonialRepository $testimonialrepositary
     * @param PlaylistRepository $playlist
     * @param FavouriteVideoRepository $favourties
     */
    public function __construct(FrontVideoRepository $videosRepository, CustomerRepository $customerrepositary, CategoryRepository $categoryRepository, SubscriptionRepository $subscriptionRepository, TestimonialRepository $testimonialrepositary, PlaylistRepository $playlist, FavouriteVideoRepository $favourties) {
        parent::__construct ();
        $this->repository = $videosRepository;
        $this->category = $categoryRepository;
        $this->subscription = $subscriptionRepository;
        $this->testimonial = $testimonialrepositary;
        $this->playlist = $playlist;
        $this->notification = new NotificationRepository ( new Notification (), new Customer () );
        $this->favouritevideos = $favourties;
        $this->customerrepositary = $customerrepositary;
        $this->homebanner = new BannerRepository ( new Banner () );
        $this->dashboardrepositary = new DashboardRepository ( new Video (), new VideoPreset (), new Option (), new Category (), new Customer (), new Comment () );
        $this->awsRepository = new AWSUploadRepository ( new TranscodedVideo (), new VideoPreset () );
    }

    /**
     * Function to send all category list
     *
     * @return json
     */
    public function browseAllCategoryVideos() {
        $trending = new RecentlyViewedVideoRepository ();
        $fetch ['categories'] = $this->getCacheData ( 'dashboard_categories', $this->category, 'getAllCategoriesSlugs' );
        $fetch ['exams'] = $this->getCacheData ( 'dashboard_exams', $this->category, 'getAllExamsByCategories' );
        $fetch ['live'] = $this->repository->getOnlyLiveVideos(4);
        if (\Auth::user ()) {
            $fetch ['profileInfo'] = $this->customerrepositary->getProfile ();
        } else {
            $fetch ['profileInfo'] = [ ];
            $fetch ['notificationCount'] = 0;
        }
        $fetch ['trending'] = $this->getCacheData ( 'dashboard_trending', $trending, 'TrendingVideos' );
        $fetch ['banner_image'] = $this->getCacheData ( 'dashboard_banner_image', $this->homebanner, 'getBannerImage' );
        $fetch ['testimonials'] = $this->getCacheData ( 'dashboard_testimonials', $this->testimonial, 'getAllTestimonials' );
        $fetch ['total_number_of_active_customer'] = $this->getCacheData ( 'dashboard_customer_count', $this->dashboardrepositary, 'getCustomersCountData', 'activecustomer' );
        $fetch ['total_number_of_active_videos'] = $this->getCacheData ( 'dashboard_video_count', $this->dashboardrepositary, 'getVideDocumentCount', 'active' );
        $fetch ['total_number_of_active_pdfdocs'] = $this->getCacheData ( 'dashboard_pdf_count', $this->dashboardrepositary, 'getVideDocumentCount', 'pdf' );
        $fetch ['total_number_of_active_audio'] = $this->getCacheData ( 'dashboard_audio_count', $this->dashboardrepositary, 'getVideDocumentCount', 'audio' );

        $fetch ['latestnews'] = LatestNews::where ( 'is_active', 1 )->orderBy ( 'id', 'desc' )->take ( 6 )->get ();
        if (array_filter ( $fetch )) {
            return $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ),'response' => $fetch ] );
        } else {
            return $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.fetch.error' ) );
        }
    }

    /**
     * Function to fetch all videos
     *
     * @return json
     */
    public function fetchPageAll() {
        if ($this->request->has ( 'type' ) && $this->request->type == 'trending') {
            $trending = new RecentlyViewedVideoRepository ();
            $fetch ['trending'] = $trending->TrendingVideos ();
        } else if ($this->request->has ( 'type' ) && $this->request->type == 'exam') {
            $fetch ['exams'] = $this->category->getAllExamsByCategories ();
        }
        return $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ),'response' => $fetch ] );
    }

    /**
     * This Function used to get particular videos (upcomming and recorded live) list
     *
     * @return json
     */
    public function getLiveVideos() {
        if ($this->request->has ( 'type' )) {
            if ($this->request->type == 'live_videos') {
                $fetch ['upcoming_live_videos'] = $this->repository->getLiveVideos ( $this->request->type );
            } else {
                $fetch ['recorded_live_videos'] = $this->repository->getrecordedLiveVideos ( $this->request->type );
            }
        }
        if (array_filter ( $fetch )) {
            return $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ),'response' => $fetch ] );
        } else {
            return $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.fetch.error' ) );
        }
    }

    /**
     * This Function used to get all upcomming and recorded live videos list
     *
     * @return json
     */
    public function browseAllLiveVideos() {
        $fetch ['server_time'] = date ( "Y-m-d H:i:s", time () );
        $serverTime = new \DateTime ( date ( "Y-m-d H:i:s", time () ) );
        $fetch ['upcoming_live_videos'] = $this->repository->getLiveVideos ();
        if (count ( $fetch ['upcoming_live_videos'] ) > 0 && isset($fetch ['upcoming_live_videos']['data'])) {
            foreach ( $fetch ['upcoming_live_videos'] ['data'] as $key => $value ) {
                $checklivetime = new \DateTime ( $value ['scheduledStartTime'] );
                $fetch ['upcoming_live_videos'] ['data'] [$key] ['liveVideoTime'] = ($checklivetime <= $serverTime);
            }
        }
        else if(count ( $fetch ['upcoming_live_videos'] ) > 0){
            foreach ( $fetch ['upcoming_live_videos'] as $key => $value ) {
                $checklivetime = new \DateTime ( $value ['scheduledStartTime'] );
                $fetch ['upcoming_live_videos'] [$key] ['liveVideoTime'] = ($checklivetime <= $serverTime);
            }
        }
        if (array_filter ( $fetch )) {
            return $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ),'response' => $fetch ] );
        } else {
            return $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.fetch.error' ) );
        }
    }

    /**
     * Function to send all video list, category list, tag list
     *
     * @return json
     */
    public function browseVideos() {
        $serverTime = new \DateTime ( date ( "Y-m-d H:i:s", time () ) );
        if ($this->request->has ( 'sub_category' )) {
            $fetch ['sub_category'] = $this->category->getChidCategory ( $this->request->sub_category );
        } else {
            $fetch ['videos'] = $this->repository->getallVideo ();
            if ($this->request->header ( 'x-request-type' ) !== 'mobile') {
                $fetch ['categories'] = $this->category->getAllCategoriesSlugs ();
                $fetch ['tags'] = $this->repository->getallTags ();
                $fetch ['live_videos'] = $this->repository->getOnlyLiveVideos ();
                $fetch['subscription'] = SubscriptionPlan::get ();

                if (count (  $fetch ['live_videos'] ) > 0) {
                foreach ( $fetch ['live_videos'] as $key => $value ) {
                    $checklivetime = new \DateTime ( $value ['scheduledStartTime'] );
                    $fetch ['live_videos'][$key] ['liveVideoTime'] = ($checklivetime <= $serverTime);
                }
                }
            }
        }
        if (array_filter ( $fetch )) {
            return $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ),'response' => $fetch ] );
        } else {
            return $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.fetch.error' ) );
        }
    }
    /**
     * Function to fetch category based videos
     *
     * @param string $slug
     * @return json
     */
    public function categoriedVideo($slug) {
        $fetch ['categories'] = $this->category->getAllCategoriesSlugs ( $slug );
        $fetch ['tags'] = $this->repository->getallTags ();
        $fetch ['videos'] = $this->repository->getallVideo ();
        $fetch ['live_videos'] = $this->repository->getallTags ();
        if ($this->request->header ( 'x-request-type' ) !== 'mobile') {
            $fetch ['customerProfile'] = $this->customerrepositary->getProfile ();
        }
        if (array_filter ( $fetch )) {
            return $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ),'response' => $fetch ] );
        } else {
            return $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.fetch.error' ) );
        }
    }
    /**
     * Function to send all video list, category list, tag list
     *
     * @return json
     */
    public function browseVideoExam($VideoIdslug = '', $exam_id = '') {
        $this->request->request->add ( [ 'exam' => true ] );
        return $this->browseVideo ( $VideoIdslug, $exam_id );
    }
    /**
     * Function to send video details with, related videos, search tag, subscription details, comments
     *
     * @return json
     */
    public function browseVideo($slug = '', $playlist_id = '') {
        $fetchedVideos = $this->repository->getVideoSlug ( $slug );
        if (isset ( $fetchedVideos->youtube_live ) && $fetchedVideos->youtube_live !== 0) {
            if ($this->request->header ( 'x-request-type' ) == 'mobile') {
                $fetch ['video'] = $fetchedVideos->makeHidden ( [ 'video_url','youtube_id','liveStatus' ] );
                $fetch ['live_related_videos'] = $this->repository->getLiverelatedVideos ( $slug );
            } else {
                $fetch ['videos'] = $fetchedVideos->makeHidden ( [ 'video_url','youtube_id','liveStatus' ] );
            }
        } else { 
            if ($this->request->header ( 'x-request-type' ) == 'mobile') {
                $fetch ['video'] = $fetchedVideos->makeHidden ( 'hls_playlist_url' );
                if ($this->request->has ( 'exam' ) && $this->request->exam) {
                    $fetch ['related'] = [ ];
                } else { 
                    $fetch ['related'] = ($playlist_id) ? $this->playlist->getPlaylistByVideosRelated ( $playlist_id, $slug ) : $this->category->getRelatedVideoSlug ( $slug, 4, false );
                }
            } else { 
                $fetch ['related'] = ($playlist_id) ? [ ] : $this->category->getRelatedVideoSlug ( $slug, 4, false );
                $fetch ['videos'] = $fetchedVideos; 
            }
        }
        if ($this->request->header ( 'x-request-type' ) == 'mobile') {
            $fetch ['comments'] = $this->repository->getCommentsVideoSlug ( $slug, 3, false );
            $fetch ['questions'] = $this->repository->getQuestionsVideoSlug ( $slug, 3, false );
            if (isset ( $fetchedVideos->youtube_live ) && $fetchedVideos->youtube_live == 0 && $playlist_id) {
                if ($this->request->has ( 'exam' ) && $this->request->exam) {
                    $fetch ['video_exam_details'] = Group::where ( $this->repository->getKeySlugorId (), $playlist_id )->first ()->group_videos ()->where ( 'videos.' . $this->repository->getKeySlugorId (), '!=', $slug )->take ( 3 )->get ();
                } else {
                    $fetch ['video_playlist_details'] = $this->playlist->getPlaylistByVideos ( $playlist_id );
                }
            }
        } else {
            $fetch ['subscription'] = $this->subscription->getAllSubscriptions ();
            $fetch ['customerProfile'] = $this->customerrepositary->getProfile ();
            $fetch ['exam'] = $fetchedVideos->collections ()->with ( 'exams' )->get ();
            $fetch['likestatus']   = $this->repository->getLikeStatus ($slug);
            $fetch ['likescount'] = $this->repository->getLikesCount ($slug);
            $fetch ['dislikescount'] = $this->repository->getDislikesCount ($slug);
            $fetch ['watchlater'] = $this->repository->getWatchLaterStatus ($slug);
        }

        if (array_filter ( $fetch )) {
            return $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ),'response' => $fetch ] );
        } else {
            return $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.fetch.error' ) );
        }
    }
    /**
     * Function to send video details with related videos
     *
     * @return json
     */
    public function browseVideoRelated($slug) {
        $fetch = $this->category->getRelatedVideoSlug ( $slug );
        $videoResult= $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ),'response' => $fetch ] );
        if ($fetch) {
         $videoResult=$this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ),'response' => $fetch ] );
        } else {
         $videoResult=$this->getErrorJsonResponse ( [ ], trans ( 'video::videos.fetch.error' ) );
        }
        return $videoResult;
    }
    /**
     * This function used to get the related and trending videos based on type
     *
     * @return json
     */
    public function browseRelatedTrendingVideos() {
        if ($this->request->type == 'recent' || $this->request->type == 'related') {
            if (! empty ( $this->request->id )) {
                $fetch = $this->category->getRelatedVideoSlug ( $this->request->id );
            } else {
                if ($this->request->type == 'recent') {
                    $fetch ['recent'] = $this->repository->getVideoByType ( 'recent' );
                } else {
                    $return = $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.type.error' ) );
                }
            }
        } else if ($this->request->type == 'trending') {
            $fetch ['trending'] = $this->repository->getVideoByType ( 'trending' );
        } else {
            $return = $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.type.error' ) );
        }
        if ($fetch) {
            $return = $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ),'response' => $fetch ] );
        } else {
            $return = $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.fetch.error' ) );
        }
        return $return;
    }

    /**
     * Function to send video details with Comments
     *
     * @return json
     */
    public function browseVideoComments($slug) {
        $this->repository->video_id = $this->repository->getVideoSlug ( $slug );

        if ($this->request->has ( 'parent_id' ) && $this->request->has ( 'comment' )) {
            $this->request->request->add ( [ 'video_id' => $this->repository->video_id->id ] );
            if ($this->repository->commentRepository->addChildComment () === 1) {
                $success = trans ( 'video::videos.commentinsert.success' );
            } else {
                $error = trans ( 'video::videos.commentinsert.error' );
            }
        } else if ($this->request->has ( 'comment' )) {
            $this->request->request->add ( [ 'video_id' => $this->repository->video_id->id ] );
            if ($this->repository->commentRepository->addComment () === 1) {
                $success = trans ( 'video::videos.commentinsert.success' );
            } else {
                $error = trans ( 'video::videos.commentinsert.error' );
            }
        } else {
            $success = trans ( 'video::videos.fetch.success' );
            $error = trans ( 'video::videos.fetch.error' );
        }
        $fetch ['comments'] = $this->repository->getCommentsVideoSlug ( $slug );
        if ($fetch ['comments'] && ! empty ( $success )) {
            return $this->getSuccessJsonResponse ( [ 'message' => $success,'response' => $fetch ['comments'] ] );
        } else {
            return $this->getErrorJsonResponse ( [ ], $error );
        }
    }

    public function browseVideoLikes($slug) {
        $this->repository->video_id = $this->repository->getVideoSlug ( $slug );
        if ($this->request->has ( 'video_id' ) && $this->request->has ( 'type' )) {
            if ($this->repository->videolikeRepository->addVideoLike () === 1) {
                $success = trans ( 'video::videos.likesinsert.success' );
            } else {
                $error = trans ( 'video::videos.likesinsert.error' );
            }
        } else {
            $success = trans ( 'video::videos.fetch.success' );
            $error = trans ( 'video::videos.fetch.error' );
        }
        $getData = $this->getVideoDatas($slug);
        if (array_filter ( $getData )) {
            return $this->getSuccessJsonResponse ( [ 'message' => $success,'response' => $getData ] );
        } else {
            return $this->getErrorJsonResponse ( [ ], $error );
        }
    }
    public function getVideoDatas($slug){
        $fetchedVideos = $this->repository->getVideoSlug ( $slug );
       // if(isset($fetchedVideos->youtube_live)&& $fetchedVideos->youtube_live!==0){
           // $fetch ['videos'] = $fetchedVideos->withHidden ( ['video_url','youtube_id','liveStatus'] );
       // }else{
            $fetch ['videos'] = $fetchedVideos->makeHidden ( 'hls_playlist_url' );
            $fetch ['related'] = $this->category->getRelatedVideoSlug ( $slug, 4, false );
      //  }
        $fetch ['subscription'] = $this->subscription->getAllSubscriptions ();
        $fetch['likestatus']   = $this->repository->getLikeStatus ($slug);
        $fetch ['likescount'] = $this->repository->getLikesCount ($slug);
        $fetch ['dislikescount'] = $this->repository->getDislikesCount ($slug);
        $fetch ['watchlater'] = $this->repository->getWatchLaterStatus ($slug);
        return $fetch;
    }
    public function watchlater($slug) {
        $this->repository->video_id = $this->repository->getVideoSlug ( $slug );
        if ($this->request->has ( 'video_id' ) ) {
            if ($this->repository->watchlaterRepository->addWatchLater () === 1) {
                $successMsg = trans ( 'video::videos.watchlaterinsert.success' );
            } else {
                $errorMsg = trans ( 'video::videos.watchlaterinsert.error' );
            }
        } else {
            $successMsg = trans ( 'video::videos.fetch.success' );
            $errorMsg = trans ( 'video::videos.fetch.error' );
        }  
        $fetchData = $this->getVideoDatas($slug);
        if (array_filter ( $fetchData )) {
            return $this->getSuccessJsonResponse ( [ 'message' => $successMsg,'response' => $fetchData ] );
        } else {
            return $this->getErrorJsonResponse ( [ ], $errorMsg );
        }
    } 

    /**
     * This function used to get and post the comments for particular videos
     *
     * @return json
     */
    public function getandpostVideocomments() {
        return $this->browseVideoComments ( $this->request->video_id );
    }
    /**
     * This function used to get and post the questions and answers for the particular video
     *
     * @return json
     */
    public function getandpostVideoQuestions() {
        return $this->browseVideoQA ( $this->request->video_id );
    }

    /**
     * Function to send video details with Question and answers
     *
     * @return json
     */
    public function browseVideoQA($slug) {
        $this->repository->video_id = $this->repository->getVideoSlug ( $slug );
        if ($this->request->has ( 'parent_id' ) && $this->request->has ( 'question' )) {
            $this->request->request->add ( [ 'video_id' => $this->repository->video_id->id ] );
            if ($this->repository->questionRepository->addChildQuestion () === 1) {
                $success = trans ( 'video::videos.commentinsertz.success' );
            } else {
                $error = trans ( 'video::videos.commentinsertz.error' );
            }
        } else if ($this->request->has ( 'question' )) {
            $this->request->request->add ( [ 'video_id' => $this->repository->video_id->id ] );

            if ($this->repository->questionRepository->addQuestion () === 1) {
                $success = trans ( 'video::videos.commentinsertz.success' );
            } else {
                $error = trans ( 'video::videos.commentinsertz.error' );
            }
        } else {
            $success = trans ( 'video::videos.commentinsertz.success' );
            $error = trans ( 'video::videos.fetch.error' );
        }
        $fetch ['questions'] = $this->repository->getQuestionsVideoSlug ( $slug );
        if ($fetch ['questions'] && ! empty ( $success )) {
            return $this->getSuccessJsonResponse ( [ 'message' => $success,'response' => $fetch ['questions'] ] );
        } else {
            return $this->getErrorJsonResponse ( [ ], $error );
        }
    }

    /**
     * To diplayed the dashboard videos like banner, recent and trending videos
     *
     * @return json
     */
    public function postDashboard() {
        $fetch ['banner'] = $this->repository->getVideoByType ( 'banner' );
        $fetch ['recent'] = $this->repository->getVideoByType ( 'recent' );
        $fetch ['trending'] = $this->repository->getVideoByType ( 'trending' );
        $fetch ['playlists'] = $this->playlist->getPlaylistByType ();
        $liveTime = Video::where ( 'is_active', '1' )->where ( 'job_status', 'Complete' )->where ( 'scheduledStartTime', '!=', '' )->where ( 'is_archived', 0 )->where ( 'youtube_live', 1 )->where ( 'liveStatus', '!=', 'complete' )->whereRaw ( 'scheduledStartTime > "' . Carbon::now ()->toDateString () . ' 00:00:00 "' )->orderBy ( 'scheduledStartTime' )->first ();
        if (count ( $liveTime ) > 0) {
            $fetch ['live_time'] = Video::where ( 'is_active', '1' )->where ( 'job_status', 'Complete' )->where ( 'scheduledStartTime', '!=', '' )->where ( 'is_archived', 0 )->where ( 'youtube_live', 1 )->where ( 'liveStatus', '!=', 'complete' )->whereRaw ( 'scheduledStartTime > "' . Carbon::now ()->toDateString () . ' 00:00:00 "' )->orderBy ( 'scheduledStartTime' )->first ();
        } else {
            $fetch ['live_time'] = null;
        }
        if (auth ()->user ()) {
            $notificationCount = $this->notification->getNotificationCount ();
            $fetch ['mypreferences'] = $this->playlist->getmypreferenceCategoryList ();
            $fetch ['notification_count'] = $notificationCount;
            $favouriteCount = $this->favouritevideos->getFavouriteVideosCount ();
            $fetch ['favourites_count'] = $favouriteCount;
            $fetch ['subscribed_plan'] = auth()->user()->activeSubscriber ()->first ();
            $fetch ['plan_duration_left'] = '';
            if($fetch ['subscribed_plan']){
                $end = Carbon::parse($fetch ['subscribed_plan']->pivot->end_date);
                $now = Carbon::now();
                $length = $end->diffInDays($now);
                $fetch ['plan_duration_left'] = $length.' days left';
            }
        } else {
            $fetch ['mypreferences'] = [ "preference-category" => [ ] ];
            $fetch ['notification_count'] = 0;
            $fetch ['favourites_count'] = 0;
            $fetch ['subscribed_plan'] = null;
            $fetch ['plan_duration_left'] = '';
        }
        if ($fetch) {
            return $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ),'response' => $fetch ] );
        } else {
            return $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.fetch.error' ) );
        }
    }
   
   
}
