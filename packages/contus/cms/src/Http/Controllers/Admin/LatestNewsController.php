<?php

/**
 * LatestNews Controller
 * To manage the LatestNews such as create, edit and delete
 *
 * @name LatestNews Controller
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Http\Controllers\Admin;

use Contus\Cms\Repositories\LatestNewsRepository;
use Contus\Base\Controller as BaseController;
use Carbon\Carbon;

class LatestNewsController extends BaseController {
    /**
     * Construct method
     */
    public function __construct(LatestNewsRepository $LatestNewsRepository) {
        parent::__construct ();
        $this->_latestNewsRepository = $LatestNewsRepository;
        $this->_latestNewsRepository->setRequestType ( static::REQUEST_TYPE );
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\View
     */
    public function getIndex() {
        return view ( 'cms::admin.latestnews.index', [ 'latestnews' => $this->_latestNewsRepository->getAllLatestNews () ] );
    }
    /**
     * Display a listing and listing the latest new content of the resource.
     *
     * @return \Illuminate\Http\View
     */
    public function getEditLatestContent($id = '') {
        return view ( 'cms::admin.latestnews.edit', [ 'id' => $id ] );
    }
    
    /**
     * Display a listing and listing the latest new content of the resource.
     *
     * @return \Illuminate\Http\View
     */
    public function getAddBlog() {
        return view ( 'cms::admin.latestnews.add');
    }
    
    /**
     * get Grid template
     *
     * @return \Illuminate\Http\View
     */
    public function getGrid() {
        return view ( 'cms::admin.latestnews.grid' );
    }
    /**
     * get Grid template
     *
     * @return \Illuminate\Http\View
     */
    public function getGridlist() {
        return view ( 'cms::admin.latestnews.gridView' );
    }
}
