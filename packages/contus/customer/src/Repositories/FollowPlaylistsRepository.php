<?php

/**
 * Follow Playlist Repository
 *
 * To manage the functionalities related to the Customer module from Latest News Resource Controller
 *
 * @vendor Contus
 *
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
use Contus\Video\Models\Playlist;

class FollowPlaylistsRepository extends BaseRepository {

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
    public function __construct(Customer $favouriteVideos) {
        parent::__construct ();
        $this->_customer = $favouriteVideos;
    }
    /**
     * Store a newly created FOllow playlist or update the Follow Playlist.
     *
     * @vendor Contus
     *
     * @package Customer
     *
     * @return boolean
     */
    public function addFollowPlaylists() {
        $this->setRules ( [ 'playlist_slug' => 'required' ] );
        if ($this->_validate ()) {
            $date = $this->_customer->freshTimestamp ();
            $playslug = $this->request->playlist_slug;
            $selectedVideos = explode ( ',', $playslug );
            $selectedVideos = Playlist::whereIn (  $this->getKeySlugorId (), $selectedVideos )->pluck ( 'id' )->toArray ();
            $existingVideos = $this->authUser->followers ()->selectRaw('follow_playlists.playlist_id')->pluck ( 'playlist_id' )->toArray ();
           $selectedFollowers = array_diff ( $selectedVideos, $existingVideos );
            if (count ( $selectedFollowers ) > 0) {
               $this->authUser->followers ()->attach ( $selectedFollowers, [ 'created_at' => $date ] );
                return true;
            } else {
                return false;
            }
        }
    }
    /**
     * Get all Follow Playlist for a customer
     *
     * @vendor Contus
     *
     * @package Customer
     *
     * @return array
     */
    public function getAllFollowPlaylists() {
        return $this->authUser->followers ()->with(['videosCount'])->paginate ( 9 )->toArray ();
    }

    /**
     * get my playlist count in myprofile page
     */
    public function getmyfollowplaylist(){
       return  $this->authUser->followers()->get()->count();
    }
    /**
     * Delete one Follow Playlist using ID
     *
     * @vendor Contus
     *
     * @package Customer
     *
     * @param int $video_id
     *
     * @return boolean
     */
    public function deleteFollowPlaylists() {
        $this->setRules ( [ 'playlist_slug' => 'required' ] );
        if ($this->_validate ()) {
            $playslug = $this->request->playlist_slug;
            $selectedVideos = explode ( ',', $playslug );
            $selectedVideos = Playlist::whereIn ( $this->getKeySlugorId (), $selectedVideos )->pluck ( 'id' )->toArray ();
            return ($this->authUser->followers ()->detach ( $selectedVideos )) ? 1 : 0;
        }
    }
}