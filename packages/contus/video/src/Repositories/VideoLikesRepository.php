<?php

/**
 * Collection Repository
 *
 * To manage the functionalities related to the Collection module from Collection Controller
 *
 * @name CommentsRepository
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
use Contus\Video\Models\VideoLike;
use Illuminate\Support\Facades\Config;
use Contus\Notification\Repositories\NotificationRepository;

class VideoLikesRepository extends BaseRepository {
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $videolikes;

    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package Video
     * @param Contus\Video\Models\Collection $collection
     */
    public function __construct(VideoLike $videolikes, NotificationRepository $notificationRepository) {
        parent::__construct ();
        $this->videolikes = $videolikes;
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
    public function addVideoLike() {
        $videoId = $this->request->video_id;
        $customerId = $this->authUser->id;
        $getData = $this->videolikes->where('customer_id','=',$customerId)->where('video_id','=',$videoId)->first();   
        $this->videolikes->video_id = $videoId;
        $this->videolikes->customer_id = $customerId;
        $count = array();
        if($this->request->type == 'like'){
            $this->videolikes->like_count = 1;
            $this->videolikes->dislike_count = 0;
            $count['like_count'] = 1;
            $count['dislike_count'] = 0;
        }
        else{
            $this->videolikes->like_count = 0;
            $this->videolikes->dislike_count = 1;
            $count['like_count'] = 0;
            $count['dislike_count'] = 1;
        }
        if(count($getData)>0){
            $status = ($this->videolikes->where('customer_id',$customerId)->where('video_id','=',$videoId)->update ($count)) ? 1 : 0;
        }
        else{
            $status = ($this->videolikes->save ()) ? 1 : 0;
    }
    return $status;
    }
    public function getCountByCustomer($videoId){
        return $this->videolikes->where('customer_id','=',$this->authUser->id)->where('video_id','=',$videoId)->first();
    }
    
}
