<?php

/**
 * Favourite Video Repository
 *
 * To manage the functionalities related to the Customer module from Latest News Resource Controller
 *
 * @name LatestNewsRepository
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

class FavouriteVideoRepository extends BaseRepository
{

    /**
     * Class property to hold the key which hold the Favourite Video object
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
    public function __construct(Customer $favouriteVideos)
    {
        parent::__construct();
        $this->_customer = $favouriteVideos;
    }

    /**
     * Store a newly created Favourite Video or update the Favourite Video.
     *
     * @vendor Contus
     *
     * @package Customer
     *
     * @param $video_id input
     *
     * @return boolean
     */
    public function addFavouriteVideos()
    {
        $this->setRules(['video_slug' => 'required']);
        if ($this->_validate()) {
            $date = $this->_customer->freshTimestamp();
            $video_id = $this->request->video_slug;
            $selectedVideos = explode(',', $video_id);

            $selectedVideos = Video::whereIn($this->getKeySlugorId(), $selectedVideos)->pluck('id')->toArray();
            $existingVideos = $this->authUser->favourites()->selectRaw('favourite_videos.video_id')->pluck('video_id')->toArray();
            $selectedFavourites = array_diff($selectedVideos, $existingVideos);
            if (count($selectedFavourites) > 0) {
                $this->authUser->favourites()->attach($selectedFavourites, ['created_at' => $date]);
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Get all Favourite Videos for a customer
     *
     * @vendor Contus
     *
     * @package Customer
     *
     * @return array
     */
    public function getAllFavouriteVideos()
    {
        $myFavouritevideo = $this->authUser->favourites()->paginate(9);
        return $myFavouritevideo->toArray();
    }

    /**
     * Get Total count for Favourite Videos of a customer
     *
     * @return array
     */
    public function getFavouriteVideosCount()
    {
        return $this->authUser->favourites()->get()->count();
    }

    /**
     * Delete one Favourite Video using ID
     *
     * @vendor Contus
     *
     * @package Customer
     *
     * @param int $video_id
     *
     * @return boolean
     */
    public function deleteFavouriteVideo()
    {
        $this->setRules(['video_slug' => 'required']);
        if ($this->_validate()) {
            $video_id = $this->request->video_slug;
            $selectedVideos = explode(',', $video_id);
            $selectedVideos = Video::whereIn($this->getKeySlugorId(), $selectedVideos)->pluck('id')->toArray();
            return ($this->authUser->favourites()->detach($selectedVideos)) ? 1 : 0;
        }
    }
}