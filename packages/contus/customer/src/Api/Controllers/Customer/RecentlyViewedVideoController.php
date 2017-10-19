<?php

/**
 * Customer Recently viewed videos Controller
 *
 * @vendor Contus
 * @package Customer
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Customer\Api\Controllers\Customer;

use Contus\Base\ApiController;
use Contus\Customer\Models\Customer;
use Contus\Customer\Repositories\RecentlyViewedVideoRepository;

class RecentlyViewedVideoController extends ApiController {

    /**
     * Construct method
     */
    public function __construct(RecentlyViewedVideoRepository $TemplatesRepository) {
        parent::__construct ();
        $this->repository = $TemplatesRepository;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
    }
    /**
     * Funtion to list all the recently viewed videos
     *
     * @return \Contus\Base\response
     */
    public function index() {
       $video['recent'] = $this->repository->fetchallforCustomer ();
        return ($video['recent']) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'customer::recentlyviewed.showSucess' ),'response' => $video['recent'] ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::recentlyviewed.showError' ) );
    }
    /**
     * Funtion to store newly added videos
     *
     * @return \Contus\Base\response
     */
    public function store() {
        return ($this->request->has('video_id') && $this->repository->addRecentVideos ($this->request->video_id)) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'customer::recentlyviewed.add.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::recentlyviewed.add.error' ) );
    }
}
