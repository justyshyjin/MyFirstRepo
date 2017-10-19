<?php

/**
 * Auth Controller
 *
 * @name       AuthController
 * @vendor     Contus
 * @package    User
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\User\Http\Controllers\Admin;

use Contus\User\Models\User;
use Contus\User\Traits\AuthendicateTrait;
use Illuminate\Support\Facades\Session;
use Validator;
use Illuminate\Http\Request;
use Contus\Base\Controller as BaseController;
use Contus\User\Repositories\AuthRepository;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Contus\Base\Helpers\StringLiterals;
use Illuminate\Support\Facades\Mail;

use Illuminate\Foundation\Auth\ThrottlesLogins;

class AuthController extends BaseController
{
    /*
     * |-------------------------------------------------------------------------- | Registration & Login Controller |-------------------------------------------------------------------------- | | This controller handles the registration of new users, as well as the | authentication of existing users. By default, this controller uses | a simple trait to add these behaviors. Why don't you explore it? |
     */
    use AuthendicateTrait, ValidatesRequests;
    /**
     * Class property to hold after login redirect path
     *
     * @vendor     Contus
     * @package    User
     * var string
     */
    public $redirectTo = null;
    public $redirectAfterLogout = "admin/auth/login";
    /**
     * Class property to hold after login validation redirect path
     *
     * @vendor     Contus
     * @package    User
     * var string
     */
    public $loginPath = null;
    /**
     * Class property to hold authentication config values
     *
     * var array
     */
    public $authenticationConfig = [];

    /**
     * Class property to hold Throttle lockoutTime time in seconds
     *
     * var string
     */
    public $lockoutTime = null;

    /**
     * Class property to hold Throttle maximum login attempts count
     *
     * var string
     */
    public $maxLoginAttempts = null;

    /**
     * Create a new authentication controller instance.
     *
     * @vendor     Contus
     * @package    User
     * @return void
     */
    public function __construct(AuthRepository $authRepository)
    {
        $this->_authRepository = $authRepository;
        $this->loginPath = 'admin' . DIRECTORY_SEPARATOR . 'auth' . DIRECTORY_SEPARATOR . 'login';
        $this->authenticationConfig = config('settings.security-settings.authentication');
        $this->lockoutTime = $this->authenticationConfig ['lockout_time'];
        $this->maxLoginAttempts = $this->authenticationConfig ['max_login_attempts'];
        config('auth.model', User::class);
        $this->middleware('guest', ['except' => ['logout']]);
    }

    /**
     * To get the Index page.
     *
     * @vendor     Contus
     * @package    User
     * @return void
     */
    public function getIndex()
    {
        return redirect('admin/login');
    }

    /**
     * To get the admin login page.
     *
     * @vendor     Contus
     * @package    User
     * @return void
     */
    public function getLogin()
    {
        return view('user::admin.login');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @vendor     Contus
     * @package    User
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            StringLiterals::EMAIL => 'required|email|max:255|unique:users',
            StringLiterals::PASSWORD => 'required|confirmed|min:6'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @vendor     Contus
     * @package    User
     * @param array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data ['name'],
            StringLiterals::EMAIL => $data [StringLiterals::EMAIL],
            StringLiterals::PASSWORD => bcrypt($data [StringLiterals::PASSWORD])
        ]);
    }

    /**
     * Handle a login request to the application.
     *
     * @vendor     Contus
     * @package    User
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $this->redirectTo = 'admin/dashboard';
        $this->validate($request, [
            $this->username() => 'required',
            StringLiterals::PASSWORD => 'required'
        ]);
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.

        $throttleBool = false;
        $throttles = ($this->authenticationConfig ['brute_force_login_attempt'] == 'Yes') ? $this->isUsingThrottlesLoginsTrait() : $throttleBool;

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->credentials($request);

        if (Auth::attempt($credentials, $request->has(StringLiterals::REMEMBER))) {

            if ($this->handleActiveUser()) {

                $authHandle = redirect($this->loginPath)->withInput($request->only($this->username(), StringLiterals::REMEMBER))->withErrors([
                    $this->username() => trans("user::adminuser.login_inactive")
                ]);
            } else {

                $authHandle = $this->handleUserWasAuthenticated($request, $throttles);
            }
            return $authHandle;

        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }

        return redirect($this->loginPath)->withInput($request->only($this->username(), StringLiterals::REMEMBER))->withErrors([
            $this->username() => $this->sendFailedLoginResponse($request)
        ]);
    }

    /**
     * To handle the active user
     *
     * @vendor     Contus
     * @package    User
     * @return \Illuminate\Http\View
     */
    public function handleActiveUser()
    {
        if (Auth::user()->is_active == 0) {
            Auth::logout();
            return true;
        }
    }

    /**
     * Show the form for forgot password.
     *
     * @vendor     Contus
     * @package    User
     * @return \Illuminate\Http\View
     */
    public function getForgotPassword()
    {
        return view('user::admin.forgotpassword');
    }

    /**
     * Reset password and the password send to registered email
     *
     * @vendor     Contus
     * @package    User
     * @return \Illuminate\Http\Response
     */
    public function postForgotPassword(Request $request){
     if ($this->_authRepository->resetAndUpdatePassword ()) {
      return redirect('admin/auth/login')->withSuccess ( trans('user::auth.forgotpassword.success') );
     } else {
      return redirect('admin/auth/login')->withInput ()->withErrors ( trans('user::auth.forgotpassword.not_registered') );
     }
    }
}
