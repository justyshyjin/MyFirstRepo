<?php

/**
 * Customer Repository
 *
 * To manage the functionalities related to the Customer module from Customer Controller
 *
 * @vendor Contus
 * @package Customer
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 *
 */
namespace Contus\Customer\Repositories;

use Contus\Base\Repository as BaseRepository;
use Illuminate\Support\Facades\Hash;
use Contus\Base\Helpers\StringLiterals;
use Contus\Customer\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Contus\Cms\Repositories\EmailTemplatesRepository;
use Contus\Notification\Repositories\NotificationRepository;
use Contus\Video\Models\Collection;
use Contus\Customer\Traits\CustomerTrait as CustomerTrait;
use Contus\Video\Repositories\PlaylistRepository;
use Contus\Video\Models\Playlist;
use Contus\Customer\Models\MypreferencesVideo;
use Contus\Video\Models\Category;
use Carbon\Carbon;
use Contus\Customer\Models\SubscriptionPlan;
use Contus\Customer\Models\Subscribers;
use Contus\Payment\Models\PaymentTransactions;

class CustomerRepository extends BaseRepository {
    use CustomerTrait;
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_customer;
    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package Customer
     * @param Contus\Customer\Models\Customer $customer
     */
    public function __construct(Customer $customer, EmailTemplatesRepository $emailTemplates) {
        parent::__construct ();
        $this->_customer = $customer;
        $this->email = $emailTemplates;
        $this->notification = new NotificationRepository ();
        if (config ()->get ( 'auth.table' ) === 'customers') {
            $this->setRules ( [ 'name' => 'required|max:100|min:3','exam' => 'required',StringLiterals::EMAIL => 'required|max:100|email|unique:customers,email,NULL,id,deleted_at,NULL','phone' => 'required|numeric|min:6','acesstype' => StringLiterals::REQUIRED,StringLiterals::PASSWORD => 'required|confirmed|min:6','password_confirmation' => 'required|same:password|min:6' ] );
        } else {
            $this->setRules ( [ 'start_date' => 'sometimes|required','subscription_plan' => 'sometimes|required','orderid' => 'sometimes|required|numeric','is_active' => 'required|boolean','exam' => 'required','name' => 'required|max:100',StringLiterals::EMAIL => 'required|max:100|email|unique:customers,email,NULL,id,deleted_at,NULL','phone' => 'required|numeric|min:6','age' => 'required','acesstype' => StringLiterals::REQUIRED,StringLiterals::PASSWORD => 'required|confirmed|min:6','password_confirmation' => 'required|same:password|min:6' ] );
        }
    }
    /**
     * Store a newly created customer or update the customer.
     *
     * @vendor Contus
     *
     * @package Customer
     * @param $id input
     * @return boolean|\Contus\Customer\Models\Customer
     */
    public function addOrUpdateCustomers($id = null) {
        $existingUser = '';
        if (! empty ( $id )) {
            $customer = $this->_customer->find ( $id );
            $existingUser = $customer->id;
            if (! is_object ( $customer )) {
                return false;
            }
            $this->setRule ( StringLiterals::EMAIL, 'sometimes|required|max:100|email|unique:customers,id,' . $customer->id );
            $this->setRule ( 'phone', 'sometimes:required|min:6' );
            $this->setRule ( 'age', 'sometimes|required' );
            $this->setRule ( 'acesstype', 'sometimes|required' );
            $this->setRule ( 'is_active', 'sometimes|required|boolean' );
            $this->setRule ( 'password', 'sometimes|required|confirmed|min:6' );
            $this->setRule ( 'exam', 'sometimes|required' );
            $this->setRule ( 'password_confirmation', 'sometimes|required|same:password|min:6' );
            $this->_validate ();
            $customer->updator_id = $this->authUser->id;
            if ($this->request->has ( 'age' ) && $this->request->age) {
                $customer->dob = $this->request->age;
            }
            if ($this->request->has ( 'password' ) && $this->request->password) {
                $customer->password = Hash::make ( $this->request->password );
            }
        } else {
            $this->_validate ();
            $softDeletedCustomers = $this->findSoftDeletedUser ()->where ( 'email', $this->request->email )->first ();
            if (is_object ( $softDeletedCustomers ) && $softDeletedCustomers->email === $this->request->email) {
                $customer = $softDeletedCustomers;
            } else {
                $customer = new Customer ();
            }
            $customer->is_active = 1;
            $customer->creator_id = (isset ( $this->authUser->id )) ? $this->authUser->id : 0;
            if ($this->request->has ( 'age' ) && $this->request->age) {
                $customer->dob = $this->request->age;
            }
            $customer->access_token = $this->randomCharGen ( 30 );
            $customer->password = Hash::make ( $this->request->password );
        }
        $customer->fill ( $this->request->except ( '_token' ) );
        if (isset ( $softDeletedCustomers ) && is_object ( $softDeletedCustomers ) && $softDeletedCustomers->email === $this->request->email) {
            $customer->restore ();
        }
        $customer->save ();
        if ($this->request->has ( 'exam' ) && $this->request->exam) {
            $exams = Collection::whereIn ( $this->getKeySlugorId (), explode ( ",", $this->request->exam ) )->lists ( 'id' )->toArray ();
            $customer->exams ()->detach ();
            $customer->exams ()->attach ( $exams );
        }
        if ($existingUser === '') {
            // array_combine_function
            $preference = new PlaylistRepository ( new Playlist (), new MypreferencesVideo () );
            $category = Category::where ( 'level', 1 )->whereNotNull ( 'preference_order' )->where ( 'is_active', 1 )->orderBy ( 'preference_order' )->lists ( 'id' )->toArray ();
            $catType = [ ];
            foreach ( $category as $k => $v ) {
                $catType [$k] = 'sub-categories';
            }
            $preference->array_combine_function ( $category, $catType, $customer->id );
            $this->email = $this->email->fetchEmailTemplate ( 'new_register' );
            $this->email->content = str_replace ( [ '##USERNAME##','' ], [ $customer->name,'' ], $this->email->content );
            $this->notification->email ( $customer, $this->email->subject, $this->email->content );
        } else {
            $this->email = $this->email->fetchEmailTemplate ( 'update_user' );
            $this->email->content = str_replace ( [ '##NAME##','' ], [ $customer->name,'' ], $this->email->content );
            $this->notification->email ( $customer, $this->email->subject, $this->email->content );
        }

        $customer = $this->_customer->find ( $customer->id );
        return $customer->makeHidden ( [ 'id','access_token' ] );
    }
    /**
     * This function used for Social registration
     *
     * @return number|\Contus\Customer\Models\Customer
     */
    public function socialRegister($id = null) {
        $this->setRules ( [ 'acesstype' => 'required','login_type' => 'required','email' => 'required|max:100|email','name' => 'required','password' => 'required|confirmed|min:6' ] );
        $this->_validate ();
        $type = ($this->request->login_type == 'fb') ? 'facebook' : (($this->request->login_type == 'google+') ? 'google' : '');
        return $this->registerSocialUser ( $this->request->all (), $type );
    }

    /**
     * This function used to save the new password for the particular user
     *
     * @param string $random
     * @return boolean
     */
    public function savenewPassword($random) {
        $this->setRules ( [ 'password' => 'required|min:6','password_confirmation' => 'required|same:password|min:6' ] );
        $this->_validate ();
        $checkuser = $this->_customer->where ( 'forgot_password', $random )->first ();
        if (count ( $checkuser ) > 0) {
            $checkuser->password = Hash::make ( $this->request->password );
            $checkuser->forgot_password = null;
            $checkuser->save ();
            return true;
        } else {
            return false;
        }
    }
    /**
     * Function to check the credentials email and password
     *
     * @vendor Contus
     *
     * @package Customer
     * @return \Contus\Customer\Models\Customer
     */
    public function checkCustomers() {
        if (isset ( $this->request->login_type ) && ! empty ( $this->request->login_type ) && $this->request->login_type == 'normal') {
            $this->setRules ( [ 'login_type' => 'required',StringLiterals::EMAIL => 'required|max:100|email',StringLiterals::PASSWORD => 'required|min:6' ] );
            $this->_validate ();
            if (Auth::attempt ( [ 'email' => $this->request->email,'password' => $this->request->password,'is_active' => 1 ] )) {
                $user = Auth::user ();
                Auth::logout ();
                $user->access_token = $this->randomCharGen ( 30 );
                if ($this->request->header ( 'x-request-type' ) == 'mobile') {
                    $user->device_type = $this->request->device_type;
                    $user->device_token = $this->request->device_token;
                    $user->acesstype = "mobile";
                } else {
                    $user->acesstype = "web";
                }
                return ($user->save ()) ? $user->makeHidden ( [ 'id','access_token' ] ) : 0;
            }
        }
        return false;
    }
    /**
     * This function used to check the logout information for IOS device token make empty
     *
     * @return number|boolean
     */
    public function checkIOSCustomers() {
        if (isset ( $this->request->device_type ) && ! empty ( $this->request->device_type ) && $this->request->device_type == 'IOS') {
            $user = Customer::where ( 'id', $this->request->id )->first ();
            if (count ( $user ) > 0) {
                $user->device_token = '';
                $user->acesstype = "mobile";
                $user->device_type = $this->request->device_type;
            }
            return ($user->save ()) ? $user->makeHidden ( [ 'id','access_token' ] ) : 0;
        }
        return false;
    }

    /**
     * This function used for the social login Customers
     *
     * @return number|\Contus\Customer\Models\Customer
     */
    public function checksocialCustomers() {
        if (isset ( $this->request->login_type ) && ! empty ( $this->request->login_type ) && $this->request->login_type != 'normal') {
            $this->setRules ( [ 'login_type' => 'required','email' => 'required|max:100|email','token' => 'required','social_user_id' => 'required','name' => 'required' ] );
            $this->_validate ();

            $type = ($this->request->login_type == 'fb') ? 'facebook' : (($this->request->login_type == 'google+') ? 'google' : '');
            return $this->registerSocialUser ( $this->request->all (), $type );
        }
        return false;
    }

    /**
     * This function used for Update the notification Status based on on/off status
     *
     * @return number|\Contus\Customer\Models\Customer
     */
    public function UpdateCustomerNotificationStatus() {
        if (isset ( $this->request->type ) && ($this->request->type == "on")) {
            $customer_id = $this->authUser->id;
            $notificationStatus = $this->_customer->where ( 'id', $customer_id )->update ( [ 'notification_status' => 1 ] );
        } else if (isset ( $this->request->type ) && ($this->request->type == "off")) {
            $customer_id = $this->authUser->id;
            $notificationStatus = $this->_customer->where ( 'id', $customer_id )->update ( [ 'notification_status' => 0 ] );
        }
        return $notificationStatus;
    }
    /**
     * Change password by checking old password and validating new passwords
     *
     * @vendor Contus
     *
     * @package Customer
     * @return object|boolean
     */
    public function changePassword() {
        $this->setRules ( [ 'old_password' => 'required|min:6',StringLiterals::PASSWORD => 'required|same:password_confirmation|min:6|different:old_password','password_confirmation' => 'required|same:password|min:6' ] );
        $this->_validate ();
        if (Hash::check ( $this->request->old_password, $this->authUser->password )) {
            $this->authUser->password = Hash::make ( $this->request->password );
            $this->authUser->save ();
            $user = $this->authUser;
            $this->email = $this->email->fetchEmailTemplate ( 'change_password' );
            $this->email->content = str_replace ( [ '##USERNAME##','##CHANGEPASSWORD##' ], [ $user->name,'' ], $this->email->content );
            $this->notification->email ( $user, $this->email->subject, $this->email->content );
            return $this->authUser->makeHidden ( 'id' );
        } else {
            return false;
        }
    }
    /**
     * Function to generate random number for OTP
     *
     * @vendor Contus
     *
     * @package Customer
     * @return boolean
     */
    public function generateResetPassword() {
        $this->setRules ( [ StringLiterals::EMAIL => 'required|max:100|email' ] );
        $this->_validate ();
        $this->_customer = $this->_customer->where ( 'email', $this->request->email )->first ();
        if (isset ( $this->_customer ) && is_object ( $this->_customer ) && ! empty ( $this->_customer->id )) {
            $this->_customer->access_otp_token = mt_rand ();
            $this->_customer->save ();
            $this->email = $this->email->fetchEmailTemplate ( 'password_reset_otp' );
            $this->email->content = str_replace ( [ '##USERNAME##','##OTP##' ], [ $this->_customer->name,$this->_customer->access_otp_token ], $this->email->content );
            $this->notification->email ( $this->_customer, $this->email->subject, $this->email->content );
            return true;
        }
        return false;
    }

    /**
     * Function to Check the OTP generated and reset password
     *
     * @vendor Contus
     *
     * @package Customer
     * @return boolean
     */
    public function otpResetPassword() {
        $this->setRules ( [ StringLiterals::EMAIL => 'required|max:100|email','access_otp_token' => 'required|numeric','acesstype' => StringLiterals::REQUIRED,StringLiterals::PASSWORD => 'required|confirmed|min:6','password_confirmation' => 'required|same:password|min:6' ] );
        $this->_validate ();
        $this->_customer = $this->_customer->where ( [ 'email' => $this->request->email,'access_otp_token' => $this->request->access_otp_token ] )->first ();
        if (isset ( $this->_customer ) && is_object ( $this->_customer ) && ! empty ( $this->_customer->id )) {
            $this->_customer->access_token = $this->randomCharGen ( 30 );
            $this->_customer->access_otp_token = '';
            $this->_customer->acesstype = $this->request->acesstype;
            $this->_customer->password = Hash::make ( $this->request->password );
            if ($this->_customer->save ()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Function to register Social User
     *
     * @param array $userDetails
     * @param string $type
     * @return number|\Contus\Customer\Models\Customer
     */
    public function registerSocialUser($userDetails, $type) {
        if (! filter_var ( $userDetails ['email'], FILTER_VALIDATE_EMAIL )) {
            return 0;
        }
        $softDeletedCustomers = $this->findSoftDeletedUser ()->where ( 'email', $userDetails ['email'] )->where ( $type . '_user_id', $userDetails ['social_user_id'] )->first ();
        if (is_object ( $softDeletedCustomers ) && $softDeletedCustomers->email === $userDetails ['email']) {
            $user = $softDeletedCustomers;
            $user->access_token = $this->randomCharGen ( 30 );
            $user->restore ();
        } else {
            $user = $this->_customer->where ( $type . '_user_id', $userDetails ['social_user_id'] )->first ();
        }
        if (! $user) {
            $normalUser = $this->_customer->where ( 'email', $userDetails ['email'] )->first ();
            if ($normalUser) {
                if ($type == 'google') {
                    $normalUser->google_user_id = $userDetails ['social_user_id'];
                    $normalUser->google_auth_id = $userDetails ['token'];
                }
                if ($type == 'facebook') {
                    $normalUser->facebook_user_id = $userDetails ['social_user_id'];
                    $normalUser->facebook_auth_id = $userDetails ['token'];
                }
                $normalUser->access_token = $this->randomCharGen ( 30 );
                if ($this->request->header ( 'x-request-type' ) == 'mobile') {
                    $normalUser->device_type = $userDetails ['device_type'];
                    $normalUser->device_token = $userDetails ['device_token'];
                }
                $normalUser->save ();
                $user = $normalUser;
            } else {
                $user = $this->_customer;
                if ($type == 'google') {
                    $user->google_user_id = $userDetails ['social_user_id'];
                    $user->google_auth_id = $userDetails ['token'];
                }
                if ($type == 'facebook') {
                    $user->facebook_user_id = $userDetails ['social_user_id'];
                    $user->facebook_auth_id = $userDetails ['token'];
                }
                $user->name = (isset ( $user->name )) ? $user->name : $userDetails ['name'];
                $user->email = $userDetails ['email'];
                $user->access_token = $this->randomCharGen ( 30 );
                $user->login_type = $userDetails ['login_type'];
                $user->is_active = 1;
                if (isset ( $userDetails ['password'] )) {
                    $user->password = Hash::make ( $userDetails ['password'] );
                }
                $user->profile_picture = (isset ( $user->profile_picture )) ? $user->profile_picture : $userDetails ['profile_picture'];

                if (isset ( $userDetails ['device_type'] )) {
                    $user->device_type = $userDetails ['device_type'];
                }
                if (isset ( $userDetails ['device_token'] )) {
                    $user->device_token = $userDetails ['device_token'];
                }
                $user->save ();
                // array_combine_function
                $preference = new PlaylistRepository ( new Playlist (), new MypreferencesVideo () );
                $category = Category::where ( 'level', 1 )->whereNotNull ( 'preference_order' )->where ( 'is_active', 1 )->orderBy ( 'preference_order' )->lists ( 'id' )->toArray ();
                $catType = [ ];
                foreach ( $category as $k => $v ) {
                    $catType [$k] = 'sub-categories';
                }
                $preference->array_combine_function ( $category, $catType, $user->id );
            }
        } else {
            $user = $this->loginSocialRegisteredUsers ( $user, $userDetails );
        }
        $this->checkAndAddExams ( $user );
        return $user->makeHidden ( [ 'id','access_token' ] );
    }
    /**
     * Function to login Already registerd Users with social login
     *
     * @param object $user
     * @param array $userDetails
     * @return object
     */
    private function loginSocialRegisteredUsers($user, $userDetails) {
        if (empty ( $user->login_type )) {
            $user->login_type = $userDetails ['login_type'];
        }
        $user->access_token = $this->randomCharGen ( 30 );
        if (isset ( $userDetails ['device_type'] )) {
            $user->device_type = $userDetails ['device_type'];
        }
        if (isset ( $userDetails ['device_token'] )) {
            $user->device_token = $userDetails ['device_token'];
        }
        $user->profile_picture = ($user->profile_picture) ? $user->profile_picture : $userDetails ['profile_picture'];
        $user->name = ($user->name) ? $user->name : $userDetails ['name'];
        $user->save ();
        return $user;
    }
    /**
     * Function to add exams for empty exam users
     *
     * @param object $user
     * @return boolean
     */
    public function checkAndAddExams($user) {
        $exams = $user->exams ()->where ( 'is_active', 1 )->lists ( 'collections.id' )->toArray ();
        if (! $exams) {
            $exams = Collection::where ( 'is_active', 1 )->lists ( 'id' )->toArray ();
            $user->exams ()->attach ( $exams );
        }
        return true;
    }

    /**
     * this function is used to reset the user password and
     * assign the new password to user
     * @input username and type of communication
     *
     * @return response
     */
    public function forgotPassword() {
        $this->setRules ( [ 'email' => 'required|exists:customers,email' ] );
        $this->setMessages ( 'email.exists', "This email id is not registered" );
        $this->_validate ();
        $newPassword = str_random ( 8 );
        $user = $this->_customer->where ( 'email', $this->request->email )->first ();
        $user->forgot_password = $newPassword;
        $user->save ();
        if (count ( $user ) > 0) {
            $this->email = $this->email->fetchEmailTemplate ( 'forgot_password' );
            $this->email->content = str_replace ( [ '##USERNAME##','##FORGOTPASSWORD##' ], [ $user->name,url ( 'auth/change_password' ) . '/' . $user->forgot_password ], $this->email->content );
            $this->notification->email ( $user, $this->email->subject, $this->email->content );
        }
        return true;
    }
    /**
     * Function to add subscription
     *
     * @return boolean
     */
    public function addSubscription() {
        $custId = $this->request->id;
        $customerObj = $this->_customer->find ( $custId );
        if (! is_object ( $customerObj )) {
            return false;
        }
        $fromDate = $this->request->start_date;
        $planId = $this->request->subscription_plan;
        $orderId = $this->request->orderid;
        $getPlan = SubscriptionPlan::where ( 'id', $planId )->first ();
        $duration = $getPlan->duration;
        $planName = $getPlan->name;
        $date = Carbon::createFromFormat ( 'd-m-Y', $fromDate );
        $fromDate = Carbon::createFromFormat ( 'd-m-Y', $fromDate )->format ( 'Y-m-d' );
        $endDate = $date->addDays ( $duration );
        $customerObj->expires_at = $endDate;
        $customerObj->save ();
        $subscriber = new Subscribers ();
        $subscriber->subscription_plan_id = $planId;
        $subscriber->customer_id = $custId;
        $subscriber->start_date = $fromDate;
        $subscriber->end_date = $endDate;
        $subscriber->is_active = 1;
        $subscriber->save ();
        $paymentTrans = new PaymentTransactions ();
        $paymentTrans->payment_method_id = 2;
        $paymentTrans->customer_id = $custId;
        $paymentTrans->status = "Success";
        $paymentTrans->transaction_message = "Success";
        $paymentTrans->transaction_id = $orderId;
        $paymentTrans->response = "Success";
        $paymentTrans->plan_name = $planName;
        $paymentTrans->subscriber_id = $custId;
        $paymentTrans->subscription_plan_id = $planId;
        $paymentTrans->save ();
        return true;
    }
}