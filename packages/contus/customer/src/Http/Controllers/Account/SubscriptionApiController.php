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
namespace Contus\Customer\Http\Controllers\Account;

use Contus\Base\Controller as BaseController;
use Contus\Customer\Repositories\SubscriptionRepository;

class SubscriptionApiController extends BaseController {
    /**
     * Construct method
     */
    public function __construct(SubscriptionRepository $CustomerRepository) {
        parent::__construct ();
        $this->_repository = $CustomerRepository;
        $this->_repository->setRequestType ( static::REQUEST_TYPE );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\View
     */
    public function getIndex() {
      return view ( 'customer::user.planlist.index');
    }
   
    /**
     * get Grid template
     *
     * @return \Illuminate\Http\View
     */
    public function getGridlist() {
      return view ( 'customer::user.planlist.gridView');
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
