<?php

/**
 * Playlist Controller
 *
 * To manage the Playlist such as create, edit and delete
 *
 * @name Playlist Controller
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Api\Controllers\Admin;

use Contus\Video\Repositories\PlaylistRepository;
use Contus\Base\ApiController;
use Contus\Video\Repositories\CategoryRepository;

class PlaylistController extends ApiController {
    /**
     * Constructer method which defines the objects of the classes used.
     *
     * @param object $playlistRepository
     */
    public function __construct(PlaylistRepository $playlistRepository,CategoryRepository $category) {
        parent::__construct ();
        $this->repository = $playlistRepository;
        $this->category = $category;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
    }
    /**
     * Function to assign videos to playlist
     *
     * @return \Contus\Base\response
     */
    public function postAdd() {
        $save = $this->repository->addPlaylistVideos ();
        return ($save === true) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::playlist.added' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.addederror' ) );
    }
    /**
     * Function to delete videos from playlist
     *
     * @return \Contus\Base\response
     */
    public function postDelete() {
        $save = $this->repository->deletePlaylistVideos ();
        return ($save === true) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::playlist.removed' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.removederror' ) );
    }

    /**
     * get Information for create form
     * return various information request by the form
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo() {
        $subcategory = $this->category->getChildCategoryList();
        return $this->getSuccessJsonResponse ( [ 'info' => [ 'locale' => trans ( 'validation' ),'isActive' => [ 'In-active','Active' ],'category'=>$subcategory, ] ] );   
    }
   
    /**
     * 
     * @param unknown $id
     * @return \Contus\Base\response
     */
    public function getVideoPlaylists($id){
      $getVideoPlaylists = $this->repository->getVideoPlaylists ( $id );
      return (is_null ( $getVideoPlaylists )) ? $this->getErrorJsonResponse ( [ ], null, 404 ) : $this->getSuccessJsonResponse ( [
              'videoplaylists' => $getVideoPlaylists,
      ] );
    }
    /**
     * Get all collection to update in to list
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getPlaylistList() {
        return $this->getSuccessJsonResponse ( [
                'info' => [
                        'allPlaylists' => $this->repository->getAllPlaylistList (),
                ]
        ] );
    }
}
