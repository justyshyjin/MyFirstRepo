<?php

/**
 * StaticContent Controller
 * To manage the static content such as create, edit and delete
 *
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Http\Controllers\Customer;

use Contus\Base\Controller as BaseController;
use Carbon\Carbon;
use Contus\Cms\Repositories\LatestNewsRepository;

class LatestNewsController extends BaseController {
    /**
     * Construct method
     */
    public function __construct(LatestNewsRepository $LatestNewsRepository) {
        parent::__construct ();
        $this->_LatestNewsRepository = $LatestNewsRepository;
        $this->_LatestNewsRepository->setRequestType ( static::REQUEST_TYPE );
    }
    
    /**
     * Display a listing of the All the static content.
     *
     * @return \Illuminate\Http\View
     */
    public function getBlog() {
        return view ( 'cms::customer.latestnews.latestNewsTemplate' );
    }
    public function getBlogDetail() {
        return view ( 'cms::customer.latestnews.latestNewsDetail' );
    }
}
