<?php

/**
 * Implements of IAuthRepository
 *
 * Inteface for implementing the AuthRepository modules and functions  
 * 
 * @name       IAuthRepository
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\User\Contracts;

interface IAuthRepository {
  /**
   * Update verification code for particular user
   *
   * @param
   *          $param
   *          
   * @return boolean
   */
  public function updateVerificationCode($param);
  /**
   * Verify user using the encrypted email and otp code.
   *
   * Logged in the user using Auth loginUsingId
   *
   * @return boolean
   */
  public function doVerification();
}
