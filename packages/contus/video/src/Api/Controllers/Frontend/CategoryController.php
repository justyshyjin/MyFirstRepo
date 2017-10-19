<?php

/**
 * Category Controller
 *
 * To manage the video categories.
 *
 * @name Category Controller
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 *
 *
 */
namespace Contus\Video\Api\Controllers\Frontend;

use Illuminate\Http\Request;
use Contus\Video\Repositories\CategoryRepository;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;
use Contus\Video\Models\Video;
use Contus\Notification\Repositories\NotificationRepository;
use Contus\Notification\Models\Notification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class CategoryController extends ApiController {
    /**
     * class property to hold the instance of UploadRepository
     *
     * @var \Contus\Base\Repositories\UploadRepository
     */
    public $uploadRepository;
    /**
     * Construct method
     *
     * @param CategoryRepository $categoryRepository
     * @param UploadRepository $uploadRepository
     * @param NotificationRepository $notificationrepositary
     */
    public function __construct(CategoryRepository $categoryRepository, UploadRepository $uploadRepository, NotificationRepository $notificationrepositary) {
        parent::__construct ();
        $this->repository = $categoryRepository;
        $this->uploadRepository = $uploadRepository;
        $this->notificationrepository = $notificationrepositary;
    }

    /**
     * Get Categories for the tabs in navigation
     *
     * @return json
     */
    public function getCategoriesNav() {
        $isCategoriesUnique = $this->getCacheData ( 'dashboard_categorynave', $this->repository, 'getCategoiesNav' );
        $this->getCAcheExpiresTime ( 'youtube_live' );
        $video = Video::where ( 'youtube_live', 1 )->where ( 'is_active', 1 )->where ( 'is_archived', 0 )->where ( 'youtube_live', 1 )->where ( 'scheduledStartTime', '!=', '' )->whereRaw ( 'scheduledStartTime > "' . Carbon::now ()->toDateTimeString () . '"' )->select ( DB::raw ( 'videos.*, DATE(scheduledStartTime) as dates' ) )->whereRaw ( 'liveStatus!="complete"' )->orderBy ( 'scheduledStartTime', 'asc' )->first ();

        if (count ( $video ) > 0) {
            $video ['timer'] = ( int ) (strtotime ( $video->scheduledStartTime ) - time ());
        }
        if (\Auth::user ()) {
            $video ['notificationCount'] = $this->notificationrepository->getNotificationCount ();
        }
        return ($isCategoriesUnique) ? $this->getSuccessJsonResponse ( [ 'response' => $isCategoriesUnique,'live' => $video,'message' => 'Success' ] ) : $this->getErrorJsonResponse ( [ ], 'Failed' );
    }
    /**
     * Funtion to clear all cache
     */
    public function clearAllCache() {
        $cacheKeys = array ('category_listing_page','dashboard_categories','dashboard_exams','dashboard_categorynave','dashboard_live','dashboard_trending','dashboard_banner_image','dashboard_testimonials','dashboard_customer_count','dashboard_video_count','dashboard_pdf_count','dashboard_audio_count' );
        if (count ( $cacheKeys )) {
            for($i = 0; $i < count ( $cacheKeys ); $i ++) {
                Cache::forget ( $cacheKeys [$i] );
            }
        }
        if (Cache::has ( 'cache_keys_playlist' )) {
            $cacheKeys = Cache::get ( 'cache_keys_playlist' );
            $cacheKeys = explode ( ",", $cacheKeys );
            foreach ( $cacheKeys as $keys ) {
                Cache::forget ( $keys );
            }
            Cache::forget ( 'cache_keys_playlist' );
        }
    }
    /**
     * Get categories for the navigation
     *
     * @return json
     */
    public function getCategoriesNavList() {
        $isCategoriesUnique = $this->getCacheData ( 'category_listing_page', $this->repository, 'getCategoiesNav', true );
        return ($isCategoriesUnique) ? $this->getSuccessJsonResponse ( [ 'response' => $isCategoriesUnique,'message' => 'Success' ] ) : $this->getErrorJsonResponse ( [ ], 'Failed' );
    }

    /**
     * Get categories for the exams
     *
     * @return json
     */
    public function getCategoriesExams() {
        $data = $this->repository->browsepreferenceListAll ();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::playlist.successfetchall' ),'response' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.errorfetchall' ) );
    }
}