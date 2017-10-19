<?php

/**
 * Auth Repository
 *
 * To manage the functionalities related to the auth module
 * @name       AuthRepository
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\User\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\User\Contracts\IAuthRepository;
use Contus\User\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Contus\Base\Helpers\StringLiterals;
use Contus\Cms\Repositories\EmailTemplatesRepository;
use Contus\Notification\Repositories\NotificationRepository;


class AuthRepository extends BaseRepository implements IAuthRepository {
    
    /**
     * Construct method initialization
     *
     * Validation rule for user verification code and forgot password.
     */
    public function __construct(User $user,EmailTemplatesRepository $emailTemplates) {
        parent::__construct ();
        $this->_user = $user;
        $this->email=$emailTemplates;
        $this->notification = new NotificationRepository();
        $this->setRules ( [ 
            'otp' => 'sometimes|required',
            'email' => 'sometimes|required|email',
        ] );
    }
    /**
     * Update verification code for particular user
     *
     * @param $param
     *            
     * @return boolean
     */
    public function updateVerificationCode($param) {
        $user = $this->_user->where ( StringLiterals::EMAIL, $this->decodeParam ( $param ) )->first ();
        if (count ( $user ) > 0) {
            $user->otp = '123456';
            $user->save ();
            return true;
        } else {

            return false;
        }
    }
    /**
     * Verify user using the encrypted email and otp code.
     *
     * Logged in the user using Auth loginUsingId
     *
     * @return boolean
     */
    public function doVerification() {
        $this->validate ( $this->request, $this->getRules () );
        $user = $this->_user->where ( StringLiterals::EMAIL, $this->decodeParam ( $this->request->user ) )->where ( 'otp', $this->request->otp )->first ();
        if (count ( $user ) > 0) {
            Auth::loginUsingId ( $user->id );
            return true;
        } else {
            return false;
        }
    }
    /**
     * Reset password and update the new password for the corresponding user.
     *
     * @return boolean
     */
    public function resetAndUpdatePassword() {
        $this->validate ( $this->request, $this->getRules () );
        $user = User::where ( 'email', $this->request->email )->first ();
        if (count ( $user ) > 0) {
            $user->password = Hash::make ( (true) ? 'admin123' : $this->generatePassword() );
            
            $user->save ();
            $this->email = $this->email->fetchEmailTemplate ( 'admin_forgot' );
            $this->email->content = str_replace (['##USERNAME##','##FORGOTPASSWORD##'],[$user->name,'admin123'],$this->email->content );
            $this->notification->email ( $user, $this->email->subject, $this->email->content );
            return true;
        } else {
            return false;
        }
    }
}