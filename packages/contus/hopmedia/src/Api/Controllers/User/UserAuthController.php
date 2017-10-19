<?php

/**
 * User Auth Controller
 *
 * To manage the functionalities related to the User api auth methods
 *
 * @name UserAuthController
 * @vendor Contus
 * @package User
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2017 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Hopmedia\Api\Controllers\User;

use Contus\Base\ApiController;
use Contus\Hopmedia\Models\User;
use Contus\Hopmedia\Repositories\UserRepository;

class UserAuthController extends ApiController {
    /**
     * Construct method
     */
    public function __construct(userRepository $userRepository) {
        parent::__construct ();
        $this->repository = $userRepository;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
    }

 
    /**
     * function to login customer information using api
     *
     * @return \Contus\Base\response
     */
    public function apiLogin() {   
        if ($this->request->login_type == 'normal') {
            $user = $this->repository->checkUsers ();  
        } else {
            $user = $this->repository->checksocialUsers ();
        }
        if ($this->request->header ( 'x-request-type' ) == 'mobile') {
            $response = [ 'response' => $user,'message' => trans ( 'hopmedia::hopmedia.message.login-success' ) ];
        } else {
            $response = [ 'message' => trans ( 'hopmedia::hopmedia.message.login-success' ) ];
        }
        return (isset ( $user->id )) ? $this->getSuccessJsonResponse ( $response ) : $this->getErrorJsonResponse ( [ ], trans ( 'hopmedia::hopmedia.message.login-error' ), 401 );
    }

    /**
     * function to logout User information using api
     *
     * @return \Contus\Base\response
     */
    public function apiLogout() {
        if ($this->request->device_type == 'IOS') {
            $user = $this->repository->checkIOSCustomers ();
        }
        if ($this->request->header ( 'x-request-type' ) == 'mobile') {
            $response = [ 'response' => $user,'message' => trans ( 'customer::customer.loginsuccess' ) ];
        }
        return (isset ( $user->id )) ? $this->getSuccessJsonResponse ( $response ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::customer.loginerror' ), 401 );
    }

 
}
