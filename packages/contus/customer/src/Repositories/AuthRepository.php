<?php

/**
 * Auth Repository
 *
 * To manage the functionalities related to the auth module
 *
 * @name AuthRepository
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Customer\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Customer\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Contus\Customer\Repositories\CustomerRepository;

class AuthRepository extends BaseRepository {
    /**
     * Construct method initialization
     *
     * @vendor Contus
     *
     * @package Customer
     * Validation rule for user verification code and forgot password.
     */
    public function __construct(Customer $user,CustomerRepository $cRepository) {
        parent::__construct ();
        $this->_customerRepository = $cRepository;
        $this->_customer = $user;
    }
}