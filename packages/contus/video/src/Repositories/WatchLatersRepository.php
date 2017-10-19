<?php

/**
 * Collection Repository
 *
 * To manage the functionalities related to the Collection module from Collection Controller
 *
 * @name WatchLatersRepository
 * @vendor Contus
 * @package Collection
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Video\Models\Video;
use Contus\Video\Models\WatchLater;
use Illuminate\Support\Facades\Config;
use Contus\Notification\Repositories\NotificationRepository;

class WatchLatersRepository extends BaseRepository {
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $watchlater;

    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package Video
     * @param Contus\Video\Models\Collection $collection
     */
    public function __construct(WatchLater $watchlater, NotificationRepository $notificationRepository) {
        parent::__construct ();
        $this->watchlater = $watchlater;
        $this->notification = $notificationRepository;
    }
    /**
     * Method to add comment by validating the user
     *
     * @vendor Contus
     *
     * @package Video
     * @return number
     */
    public function addWatchLater() {
        $videoId = $this->request->video_id;
        $customerId = $this->authUser->id;
        $getData = $this->watchlater->where('customer_id','=',$customerId)->where('video_id','=',$videoId)->first();   
        $this->watchlater->video_id = $videoId;
        $this->watchlater->customer_id = $customerId;
        if(count($getData)>0){
			$count = array();
			$count['video_id'] = $videoId;
        	$count['customer_id'] = $customerId;
            $status = ($this->watchlater->where('customer_id',$customerId)->where('video_id','=',$videoId)->update ($count)) ? 1 : 0;
        }
        else{
            $status = ($this->watchlater->save ()) ? 1 : 0;
    }
    return $status;
    }
    public function getCountByCustomer($videoId){
        return $this->watchlater->where('customer_id','=',$this->authUser->id)->where('video_id','=',$videoId)->first();
    }
    
}
