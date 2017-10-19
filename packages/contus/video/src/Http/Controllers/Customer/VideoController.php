<?php

/**
 * Dashboard Controller
 *
 * To manage the Dashboard page view funtionalities
 *
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Http\Controllers\Customer;

use Contus\Base\Controller as BaseController;
use Contus\Video\Repositories\FrontVideoRepository;

class VideoController extends BaseController {
    /**
     * Construct method
     */
    public function __construct(FrontVideoRepository $repository) {
        parent::__construct ();
        $this->_repository = $repository;
        $this->_repository->setRequestType ( static::REQUEST_TYPE );
    }
    /**
     * Method to return index blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index() {
        return view ( 'video::customer.videos.index' );
    }
    /**
     * Method to return index blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function playlistIndex() {
        return view ( 'video::customer.playlists.index' );
    }
    /**
     * Method to return video index blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function allPlaylists() {
        return view ( 'video::customer.playlists.playlists' );
    }
    /**
     * Method to return video index blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function videodetail() {
        return view ( 'video::customer.videos.videodetail' );
    }
    /**
     * Method to return index blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function video() {
        return view ( 'video::customer.videos.video' );
    }
    /**
     * Method to return videos blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function allvideos() {
        return view ( 'video::customer.videos.videos' );
    }

    /**
     * Method to return live videos blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function livevideos() {
        return view ( 'video::customer.videos.livevideos' );
    }
    /**
     * Method to return Playlist videos blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function playlistVideos() {
        return view ( 'video::customer.playlists.videodetail' );
    }
    /**
     * Method to return Category list blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listCategories() {
        return view ( 'video::customer.videos.categorylist' );
    }
    /**
     * Method to return Playlist videos blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function groupList() {
        return view ( 'video::customer.exam.grouplist' );
    }
    /**
     * Method to return Playlist videos blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function groupvideodetail() {
        return view ( 'video::customer.exam.groupVideo' );
    }
    /**
     * Method to return Playlist videos blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function playlistvideodetail() {
        return view ( 'video::customer.playlists.grouplist' );
    }
    /**
     * Method to return Playlist videos blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function videodetailsidemenu() {
        return view ( 'video::customer.videos.videodetailsidemenu' );
    }
}
