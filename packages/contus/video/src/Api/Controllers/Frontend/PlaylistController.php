<?php

/**
 * Playlist Controller
 *
 * To manage the Playlist such as create, edit and delete
 *
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Api\Controllers\Frontend;

use Contus\Video\Repositories\PlaylistRepository;
use Contus\Base\ApiController;
use Contus\Video\Repositories\CategoryRepository;

class PlaylistController extends ApiController {
    /**
     * Constructer method which defines the objects of the classes used.
     *
     * @param object $playlistRepository
     */
    public function __construct(PlaylistRepository $playlistRepository, CategoryRepository $catreposity) {
        parent::__construct ();
        $this->repository = $playlistRepository;
        $this->category = $catreposity;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
    }
    /**
     * Function to fetch all playlist based on category
     *
     * @return \Contus\Base\response
     */
    public function browseCategoryPlaylist() {
        $data = $this->repository->browseSortPlaylist($this->request->sortby);
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::playlist.successfetchall' ),'response' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.errorfetchall' ) );
    }
    /**
     * This function used to recover the new password for each customer
     *
     * @return \Contus\Base\response
     */
    public function forgotpassword() {
        $data = $this->category->browseCategoryPlaylist ( $this->request->slug );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::playlist.successfetchall' ),'response' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.errorfetchall' ) );
    }
    /**
     * Function to save the category which is choosed by the registered user
     *
     * @return \Contus\Base\response
     */
    public function savepreferenceListPlaylist() {
        $data = $this->repository->savepreferenceListPlaylist ();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::playlist.Addsuccess' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.errorfetchall' ) );
    }
    /**
     * Function to fetch the sub-category and exam list
     *
     * @return \Contus\Base\response
     */
    public function preferenceListPlaylist() {
        $data = $this->category->browsepreferenceListPlaylist ();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::playlist.successfetchall' ),'response' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.errorfetchall' ) );
    }
    /**
     * Function to fetch all videos based on playlist
     *
     * @return \Contus\Base\response
     */
    public function getVideoPlaylists($id) {
        $getVideoPlaylists = $this->repository->getVideoPlaylists ( $id );
        if ($this->request->header ( 'x-request-type' ) == 'mobile') {
            return (is_null ( $getVideoPlaylists )) ? $this->getErrorJsonResponse ( [ ], null, 404 ) : $this->getSuccessJsonResponse ( [
            'response' => $getVideoPlaylists ] );
        } else {
            return (is_null ( $getVideoPlaylists )) ? $this->getErrorJsonResponse ( [ ], null, 404 ) : $this->getSuccessJsonResponse ( [ 'videoplaylists' => $getVideoPlaylists ]
             );
        }
    }
    /**
     *
     * @param unknown $slug
     * @return \Contus\Base\response
     */
    public function browsePlaylistList($slug) {
        $data = $this->repository->getPlaylistList ( $slug );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::playlist.successfetchall' ),'response' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.errorfetchall' ) );
    }

    /**
     * Function to fetch all Selected category list for the particular use
     *
     * @return \Contus\Base\response
     */
    public function mypreferenceCategoryList() {
        $data = $this->repository->getmypreferenceCategoryList ();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::playlist.successfetchall' ),'response' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.errorfetchall' ) );
    }
}
