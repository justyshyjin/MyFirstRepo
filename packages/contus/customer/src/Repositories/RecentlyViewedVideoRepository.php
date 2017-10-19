<?php

/**
 * Recently Viewed Video Repository
 *
 * To manage the functionalities related to the Customer viewed videos and lists
 *
 * @vendor Contus
 * @package Cms
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Customer\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Customer\Models\Customer;
use Contus\Video\Models\Video;
use Contus\Video\Models\Category;
use Carbon\Carbon;

class RecentlyViewedVideoRepository extends BaseRepository {

    /**
     * Class property for customer object
     *
     * @var object
     */
    protected $_customer;
    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package Customer
     *
     * @param Contus\Customer\Models\Customer $favouriteVideos
     */
    public function __construct() {
        parent::__construct ();
        $this->_customer = new Customer();
        $this->video = new Video();
    }
    /**
     * Store a newly created Recently Viewed Video.
     *
     * @vendor Contus
     *
     * @package Customer
     *
     * @param $video_id input
     *
     * @return boolean
     */
    public function addRecentVideos($videoid) {
        $selectedVideos = Video::whereIn ( $this->getKeySlugorId (), explode ( ',', $videoid ) )->pluck ( 'id' )->toArray ();
        if($selectedVideos){
            $this->authUser->recentlyViewed ()->detach ( $selectedVideos );
            $this->authUser->recentlyViewed ()->attach ( $selectedVideos );
            return true;
        }else {
            return false;
        }
    }
    /**
     * Fetch all trending videos
     *
     * @vendor Contus
     *
     * @package Customer
     *
     * @return array
     */
    public function TrendingVideos(){
            $video = $this->video->whereCustomer ();
            return $video->join ( 'recently_viewed_videos', 'videos.id', '=', 'recently_viewed_videos.video_id' )->with('categories')->where ( 'recently_viewed_videos.created_at', '>', Carbon::now ()->subDays ( 30 ) )->selectRaw ( 'videos.*,count("video_id") as count' )->groupBy ( 'recently_viewed_videos.video_id' )->where ( 'youtube_live', '==', 0 )->orderBy ( 'count', 'desc' )->paginate ( 10 )->toArray();
    }
    /**
     * Fetch all Customers
     *
     * @vendor Contus
     *
     * @package Customer
     *
     * @return array
     */
    public function fetchallforCustomer(){
        return $this->authUser->recentlyViewed()->paginate(12)->toArray();
    }
}