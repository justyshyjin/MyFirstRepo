<?php

/**
 * Playlist Resource Controller
 *
 * To manage the Playlist such as create, edit and delete
 *
 * @name Playlist Resource Controller
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Api\Controllers\Admin;

use Contus\Video\Repositories\PlaylistRepository;
use Contus\Base\ApiController;

class PlaylistResourceController extends ApiController {
    /**
     * Constructer method which defines the objects of the classes used.
     *
     * @param object $playlistRepository
     */
    public function __construct(PlaylistRepository $playlistRepository) {
        parent::__construct ();
        $this->repository = $playlistRepository;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
    }
    /**
     * Fetch all playlist by 10 pagination records from database
     *
     * @return \Contus\Base\response
     */
    public function index() {
        $data = $this->repository->getAllPlaylist ();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.errorfetchall' ) );
    }
    /**
     * Save new playlist in database
     *
     * @return \Contus\Base\response
     */
    public function store() {
        $save = $this->repository->addOrUpdatePlaylist ();
        return ($save === 1) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::playlist.created.added' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.error.added' ) );
    }
    /**
     * Update playlist details in database
     *
     * @param int $playlistId
     * @return \Contus\Base\response
     */
    public function update($playlistId) {
        $save = $this->repository->addOrUpdatePlaylist ( $playlistId );
        return ($save === 1) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::playlist.created.updated' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.error.updated' ) );
    }
    /**
     * Fetch one playlist from database
     *
     * @param int $playlistId
     * @return \Contus\Base\response
     */
    public function show($playlistId) {
        $data = $this->repository->getPlaylist ( $playlistId );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.errorfetch' ) );
    }
    /**
     * Delete one playlist from database
     *
     * @param int $playlistId
     * @return \Contus\Base\response
     */
    public function destroy($playlistId) {
        $data = $this->repository->deletePlaylist ( $playlistId );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::playlist.deleted.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.deleted.error' ) );
    }
}
