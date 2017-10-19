<?php

/**
 * Banner Controller
 * To manage the static content such as create, edit and delete
 * 
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Http\Controllers\Admin;

use Contus\Base\Controller as BaseController;
use Carbon\Carbon;
use Contus\Cms\Repositories\BannerRepository;

class BannerController extends BaseController { 
    /**
     * Construct method
     */
    public function __construct(BannerRepository $BannerRepository) {
        parent::__construct ();
        $this->_bannerRepository = $BannerRepository;
        $this->_bannerRepository->setRequestType ( static::REQUEST_TYPE );
    }
    
    /**
     * Display a listing of the The Banner management. It will be like image or video.
     *
     * @return \Illuminate\Http\View
     */
    public function getIndex() {
        return view ( 'cms::admin.banner.index');
    }
    
    /**
     * get Grid template
     *
     * @return \Illuminate\Http\View
     */
    public function getGrid() {
        return view ( 'cms::admin.banner.grid' );
    }
    /**
     * get Grid List
     *
     * @return \Illuminate\Http\View
     */
    public function getGridlist() {
        return view ( 'cms::admin.banner.gridView' );
    }
    
}
