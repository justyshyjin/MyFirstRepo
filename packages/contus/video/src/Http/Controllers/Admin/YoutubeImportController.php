<?php

/**
 * YoutubeImportController
 *
 * @vendor Contus
 *
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Http\Controllers\Admin;

use Contus\Base\Controller as BaseController;
use Contus\Video\Repositories\VideoRepository;
use Contus\Video\Repositories\YoutubeRepository;

class YoutubeImportController extends BaseController {
    public $videoRepository;
    /**
     * class property is used to initiate the class
     *
     * @vendor Contus
     *
     * @package Video
     * @var array
     */
    public function __construct(YoutubeRepository $youtubeRepository) {
        $this->repository = $youtubeRepository;
    }

    /**
     * Show the videos get uploads
     *
     * @vendor Contus
     *
     * @package Video
     * @return \Illuminate\Http\View
     */
    public function getIndex() {
        if($this->repository->authenticate ()){
            return redirect ( 'admin/youtube-live' );
        }
        return redirect ( 'admin/videos' );
    }
    /**
     * Show the videos get live
     *
     * @vendor Contus
     *
     * @package Video
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function getLive() {
        if(!$this->repository->authScheduler ()){
            return redirect ( 'admin/youtube-import' );
        }
        if ($this->repository->client->getAccessToken ()) {
            $this->repository->callYoutube ( 'getlive' );
        }
        return redirect ( 'admin/livevideos' );
    }
    /**
     * Get all youtube downloads
     *
     * @vendor Contus
     *
     * @package Video
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function getDownload() {
        $this->repository->import ();
        return redirect ( 'admin/livevideos' );
    }
}