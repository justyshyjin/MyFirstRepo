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
use Contus\Customer\Repositories\CustomerRepository;

class MyAccountController extends BaseController {
    /**
     * Construct method
     */
    public function __construct(CustomerRepository $CustomerRepository) {
        parent::__construct ();
        $this->_repository = $CustomerRepository;
        $this->_repository->setRequestType ( static::REQUEST_TYPE );
    }
    /**
     * Method to return index blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index() {
        return view ( 'customer::user.account.index' );
    }
    /**
     * Method to return myprofile blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function myprofile() {
        return view ( 'customer::user.account.myprofile' );
    }
    /**
     * Method to return change password blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function changePassword() {
        return view ( 'customer::user.account.password' );
    }
    /**
     * Method to return change password blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function editProfile() {
        return view ( 'customer::user.account.edit' );
    }
    /**
     * This function is used to get the subscription details
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function subscribeDetails() {
        return view ( 'customer::user.account.payment' );
    }
    /**
     * This function to get the favourites template
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function favourites() {
        return view ( 'customer::user.account.favourite' );
    }
    /**
     * This function to get the favourites template
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function following() {
    return view ( 'customer::user.account.follow' );
    }
}
