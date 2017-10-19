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

class SubscriptionController extends BaseController {
    /**
     * Construct method
     */
    public function __construct(SubscriptionRepository $CustomerRepository) {
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
        return view ( 'customer::user.subscription.index' );
    }
    /**
     * Method to return myprofile blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function subscrptionForm() {
        return view ( 'customer::user.subscription.subscrptionform' );
    }
}
