<?php

/**
 * Hopmedia Auth Controller
 *
 * @name UserAuthController
 * @vendor Contus
 * @package Hopmedia
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2017 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Hopmedia\Http\Controllers\User;

use Carbon\Carbon;
use Contus\Base\Controller as BaseController;
use Contus\Hopmedia\Models\User;
use Contus\Base\Helpers\StringLiterals;
use Contus\Hopmedia\Repositories\UserRepository;
use Contus\User\Traits\AuthendicateTrait;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;

class UserAuthController extends BaseController
{
    /*
     * |-------------------------------------------------------------------------- | Registration & Login Controller |-------------------------------------------------------------------------- | | This controller handles the registration of new users, as well as the | authentication of existing users. By default, this controller uses | a simple trait to add these behaviors. Why don't you explore it? |
     */
    use AuthendicateTrait, ValidatesRequests;
    /**
     * Class property to hold after login redirect path
     *
     * @vendor Contus
     *
     * @package Hopmedia
     * var string
     */
    public $redirectTo = null;
    public $redirectAfterLogout = "hopmedia/auth/login";
    /**
     * Class property to hold after login validation redirect path
     *
     * @vendor Contus
     *
     * @package User
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
     * Constructor funciton.
     *
     * @return void
     */
    public function __construct(UserRepository $authRepository, Request $request)
    {
        $this->_userRepository = $authRepository;
        $this->request = $request;
        $this->loginPath = 'hopmedia/';
        $this->authenticationConfig = config('settings.security-settings.authentication');
        $this->lockoutTime = $this->authenticationConfig ['lockout_time'];
        $this->maxLoginAttempts = $this->authenticationConfig ['max_login_attempts'];
    }

    /**
     * Funtion to check Oauth of google plus login
     *
     * @return redirect to Oauth url for google
     */
    public function getGoogle()
    {
        config()->set('services.google.redirect', url('auth/google-callback'));
        return Socialite::driver('google')->redirect();
    }

    public function getPaymnet($slug)
    {
        $data = $this->subscription->proccessSubscriptionPayment($slug);
        $ccAvenueURL = $this->subscription->getCCAvenueUrl($slug);
        if (isset($data)) {
            return view('payment::customer.payment.ccavRequestHandler')->with(array('data' => $data, 'urls' => $ccAvenueURL));
        } else {
            return false;
        }
    }

    /**
     * Function to check Oauth of facebook login
     *
     * @return redirect to Oauth url for facebook
     */
    public function getFacebook()
    {
        config()->set('services.facebook.redirect', url('auth/facebook-callback'));
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Function to get the callback from Oauth facebook authentication
     *
     * @return redirect Url
     */
    public function getFacebookCallback()
    {
        if ($this->request->error == 'access_denied') {
            return redirect('/');
        }
        config()->set('services.facebook.redirect', url('auth/facebook-callback'));
        $facebookUser = Socialite::driver('facebook')->user();
        $this->registerOrLoginUser($facebookUser, 'facebook', 'fb');
        return redirect('/');
    }

    /**
     * Function to get the callback from Oauth facebook authentication
     *
     * @return redirect Url
     */
    public function getGoogleCallback()
    { dd(1);
        if ($this->request->error == 'access_denied') {
            return redirect('/');
        }
        config()->set('services.google.redirect', url('auth/google-callback'));
        $googleUser = Socialite::driver('google')->user();
        $this->registerOrLoginUser($googleUser, 'google', 'google+');
        return redirect('/');
    }

    /**
     * Function to login or register for social login
     *
     * @param object $socialUser
     * @param string $type
     * @param string $registeredMedium
     */
    public function registerOrLoginUser($socialUser, $type, $registeredMedium)
    {
        $userDetails = array('social_user_id' => $socialUser->getId(), 'token' => $socialUser->token, 'name' => $socialUser->getName(), 'email' => $socialUser->getEmail(), 'profile_picture' => $socialUser->getAvatar(), 'login_type' => $registeredMedium);
        $user = $this->_userRepository->registerSocialUser($userDetails, $type);
        if (is_object($user) && !empty ($user->id) && ($user->is_active)) {
            $this->request->session()->put('access_token', $user->access_token);
            $this->_userRepository->checkAndAddExams($user);
            Auth::loginUsingId($user->id);
        }
    }

    /**
     * This Function used to customer can Change the password
     *
     * @param object $socialUser
     * @param string $type
     * @param string $registeredMedium
     */
    public function getChangePassword($random)
    {
        $checkRandom = User::where('forgot_password', $random)->first();
        if (!empty ($this->request->header('X-XSRF-TOKEN'))) {
            if (!empty ($checkRandom) && (count($checkRandom) > 0)) {
                $return = view('customer::user.dashboard.forgot')->with('random', $random);
            } else {
                $return = $this->getErrorJsonResponse([], trans('customer::subscription.showError'), 403);
            }
        } else {
            if (!empty ($checkRandom) && (count($checkRandom) > 0)) {
                $return = redirect('/#/forgotpassword/' . $random);
            } else {
                $return = redirect('/#/login/');
            }
        }
        return $return;
    }

    /**
     * This function used to save the new password.
     * when user forget the password change the new one and save the new one
     *
     * @param unknown $random
     */
    public function postsaveNewpassword($random)
    {
        $this->_userRepository->savenewPassword($random);
        return redirect(url('/#/login'));
    }

    /**
     * Method to logout
     *
     * @vendor Contus
     *
     * @package User
     * @return \Illuminate\Http\View
     */
    public function getLogout()
    {
        \Auth::logout();
        return redirect(url('hopmedia/#/'));
    }

    /**
     * To handle the active user
     *
     * @vendor Contus
     *
     * @package Hopmedia
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
     * Handle a login request to the application.
     *
     * @vendor     Contus
     * @package    User
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $this->redirectTo = 'hopmedia/';
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
                    $this->username() => trans("hopmedia::hopmedia.message.login_inactive")
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
     * Send the response after the user was authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @param bool $throttles
     * @return \Illuminate\Http\Response
     */
    protected function handleUserWasAuthenticated(Request $request, $throttles)
    {
        if ($throttles) {
            $this->clearLoginAttempts($request);
        }
        $now = Carbon::now();
        $end = Carbon::parse(Auth::user()->created_at);
        $mins = $end->diffInMinutes($now); 
        if ($mins == 0) {
            $redirect = $this->redirectPath() . '#/subscribeinfo';
        } else {
            $redirect = 'hopmedia/';
        }
        session()->forget('url');
        
        return redirect()->intended($request->root().'/'.$redirect);
    }
}
