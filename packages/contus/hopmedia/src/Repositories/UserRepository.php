<?php

/**
 * User Repository
 *
 * To manage the functionalities related to the user module from User Controller
 *
 * @vendor Contus
 * @package User
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2017 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 *
 */
namespace Contus\Hopmedia\Repositories;

use Contus\Base\Repository as BaseRepository;
use Illuminate\Support\Facades\Hash;
use Contus\Base\Helpers\StringLiterals;
use Contus\Hopmedia\Models\User;
use Illuminate\Http\Request;
use Contus\Base\Repositories\UploadRepository;
use Illuminate\Support\Facades\Auth;
use Contus\Cms\Repositories\EmailTemplatesRepository;
use Contus\Notification\Repositories\NotificationRepository;
use Carbon\Carbon;

class UserRepository extends BaseRepository {
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_user;
    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package User
     * @param Contus\User\Models\User $user
     */
    public function __construct(User $user, EmailTemplatesRepository $emailTemplates, UploadRepository $uploadRepository) {
        parent::__construct ();
        $this->_user = $user;
        $this->email = $emailTemplates;
        $this->notification = new NotificationRepository ();
        $this->uploadRepository = $uploadRepository;
        
    }
    /**
     * Function to check the credentials email and password
     *
     * @vendor Contus
     *
     * @package Customer
     * @return \Contus\Customer\Models\Customer
     */
    public function checkUsers() { 
        if (isset ( $this->request->login_type ) && ! empty ( $this->request->login_type ) && $this->request->login_type == 'normal') { 
            $this->setRules ( [ 'login_type' => 'required',StringLiterals::EMAIL => 'required|max:100|email',StringLiterals::PASSWORD => 'required|min:6' ] );
            $this->_validate ();
            if (Auth::attempt ( [ 'email' => $this->request->email,'password' => $this->request->password,'is_active' => 1 ] )) {
                $user = Auth::user ();
                Auth::logout ();
 
                return ($user->save ()) ? $user->makeHidden ( [ 'id','access_token' ] ) : 0;
            }
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
     * Store a newly created broadcaster user.
     *
     * @param $id input
     * values
     *
     * @vendor Contus
     * @package User
     * @return boolean
     */
    public function addOrUpdateUsers($id = null)
    { 
        $this->setRules ( [ 'name' => 'required|max:100',StringLiterals::EMAIL => 'required|max:100|email|unique:users','phone' => 'required|numeric|min:6|unique:users',StringLiterals::PASSWORD => 'required|min:6','confirm_password' => 'required|same:password|min:6','company'=>StringLiterals::REQUIRED,'domain'=> StringLiterals::REQUIRED.'|unique:users|regex:/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9](?:\.[a-zA-Z]{2,})+$/' ] );

       $this->uploadRepository->defineRepositoryFileRule($this);
        if (app()->make('request')->has(StringLiterals::PROFILE)) {
            $this->uploadRepository->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_PROFILE)->setRequestParamKey(StringLiterals::PROFILE)->setConfig();
        }
        if (!empty ($id)) {
            $adminUser = $this->_user->find($id);
            $this->setRule(StringLiterals::EMAIL, 'required|email|unique:users,email,' . $adminUser->id);
        } else { 
            $adminUser = new User ();
            $adminUser->name = $this->request->name;
            $adminUser->password = Hash::make($this->request->password);
            $adminUser->access_token = $this->randomCharGen(30);
            $adminUser->parent_id = 1;
            $adminUser->email = $this->request->email;
            $adminUser->phone = $this->request->phone;
            $adminUser->domain = $this->request->domain;
            $adminUser->company = $this->request->company;
            $adminUser->user_group_id = 2;
        }  
        $this->validate(app()->make('request'), $this->getRules());
        if (!empty($id)) {
            $adminUser->profile_image = app()->make('request')->profile_image;
        }  
        if ($adminUser->save()) {
            if (empty($id)) {
                $this->email = $this->email->fetchEmailTemplate('new_user');
                $this->email->content = str_replace(['##NAME##', '##EMAIL##', '##PASSWORD##'], [$adminUser->name, $adminUser->email, 'admin123'], $this->email->content);
            } else {
                $this->email = $this->email->fetchEmailTemplate('update_user');
                $this->email->content = str_replace(['##NAME##'], [$adminUser->name], $this->email->content);
            }
            $this->notification->email ( $adminUser, $this->email->subject, $this->email->content );
            return true;
        }
       
    }

   
}