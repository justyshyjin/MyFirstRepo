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
namespace Contus\Customer\Http\Controllers\Dashboard;

use Contus\Base\Controller as BaseController;
use Contus\Customer\Repositories\DashboardRepository;
use Contus\Customer\Repositories\CustomerRepository;

class DashboardController extends BaseController {
    /**
     * Construct method
     */
    public function __construct(CustomerRepository $DashboardRepository) {
        parent::__construct ();
        $this->_repository = $DashboardRepository;
        $this->_repository->setRequestType ( static::REQUEST_TYPE );
    }
    /**
     * Method to return index blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index() {
        return view ( 'customer::user.dashboard.index' );
    }
    /**
     * Method to return login blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function loginModel() {
        return view ( 'customer::user.dashboard.login' );
    }
    /**
     * Method to return login blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function signUpModel() {
        return view ( 'customer::user.dashboard.signup' );
    }
    /**
     * Method to return Forgot password blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function forgotModel() {
        return view ( 'customer::user.dashboard.forgot' );
    }
    /**
     * This function used to get the forgot password model
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function newpasswordModel(){
        return view ( 'customer::user.dashboard.newpassword' );
    }
    /**
     * Mehtod to list dashboard page blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function dashboard(){
        return view ('customer::user.dashboard.dashboard');
    }
}
