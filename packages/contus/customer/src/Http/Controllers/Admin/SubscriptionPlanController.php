<?php

/**
 * SubscriptionPlan Controller
 * To manage the Subscription Plans such as create, edit and delete
 * 
 * @name Subscription Controller
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Customer\Http\Controllers\Admin;

use Contus\Base\Controller as BaseController;
use Carbon\Carbon;
use Contus\Customer\Repositories\SubscriptionRepository;

class SubscriptionPlanController extends BaseController {
    /**
     * Construct method
     */
    public function __construct(SubscriptionRepository $subscriptionRepository) {
        parent::__construct ();
        $this->_subscriptionRepository = $subscriptionRepository;
        $this->_subscriptionRepository->setRequestType ( static::REQUEST_TYPE );
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\View
     */
    public function getIndex() { 
        return view ( 'customer::admin.subscriptions.index', [ 'subscriptions' => $this->_subscriptionRepository->getAllSubscriptions () ] );
    }
    
    /**
     * get Grid template
     *
     * @return \Illuminate\Http\View
     */
    public function getGrid() {
        return view ( 'customer::admin.subscriptions.grid' );
    }
    /**
     * get Grid template
     *
     * @return \Illuminate\Http\View
     */
    public function getGridlist() {
        return view ( 'customer::admin.subscriptions.gridView' );
    }
    
    /**
     * Logout admin login
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout() {
        $this->auth->user ()->where ( 'id', $this->auth->user ()->id )->update ( [ 'last_logged_out_at' => Carbon::now () ] );
        auth ()->logout ();
        return redirect ( '/' );
    }
}
