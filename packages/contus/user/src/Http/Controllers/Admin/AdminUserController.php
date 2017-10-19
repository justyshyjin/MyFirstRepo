<?php

/**
 * AdminUser Controller
 *
 * To manage the Admin users such as create, edit and delete the admin users
 *
 * @name       AdminUser Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\User\Http\Controllers\Admin;

use Contus\User\Repositories\AdminUserRepository;
use Contus\Base\Controller as BaseController;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use Contus\Base\Helpers\StringLiterals;

class AdminUserController extends BaseController {
  /**
   * Construct method
   */
  public function __construct(AdminUserRepository $adminUserRepository) {
    
    parent::__construct ();
    $this->_adminUserRepository = $adminUserRepository;
    $this->_adminUserRepository->setRequestType ( static::REQUEST_TYPE );
  }
  
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\View
   */
  public function getIndex($status = 'all') {
    return view ( 'user::admin.users.index', [ 
        'users' => $this->_adminUserRepository->getUsers ( $status ),
        'status' => $status 
    ] );
  }
  
  /**
   * Show the form for creating a new admin user.
   *
   * @return \Illuminate\Http\View
   */
  public function getAdd() {
    
    return view ( 'user::admin.users.add', [ 
        'groups' => $this->_adminUserRepository->getGroupsList (),
        StringLiterals::RULES => $this->_adminUserRepository->setRule ( 'email', 'required|email|unique' )->getRules () 
    ] );
  }
  
  /**
   * Store a newly created admin user.
   *
   * @return \Illuminate\Http\Response
   */
  public function postAdd() {
    try {
      $this->_adminUserRepository->addOrUpdateUsers ();
    } catch ( Exception $e ) {
      $this->logger->error ( $e->getMessage () );
    }
    
    return redirect ( StringLiterals::ADMIN_USERS )->withSuccess ( trans ( 'user::adminuser.success' ) );
  }
  
  /**
   * Show the form for editing the specified resource.
   *
   * @param int $id          
   *
   * @return \Illuminate\Http\View
   */
  public function getEdit($id) {
    return view ( 'user::admin.users.edit', [ 
        'user' => $this->_adminUserRepository->getUser ( $id ),
        'groups' => $this->_adminUserRepository->getGroupsList (),
        StringLiterals::RULES => $this->_adminUserRepository->setRule ( 'email', 'required|email|unique' )->getRules () 
    ] );
  }
  
  /**
   * Update the specified resource in storage.
   *
   * @param int $id          
   *
   * @return \Illuminate\Http\Response
   */
  public function postUpdate($id) {
    $this->_adminUserRepository->addOrUpdateUsers ( $id );
    return redirect ( StringLiterals::ADMIN_USERS )->withSuccess ( trans ( 'user::adminuser.updated' ) );
  }
  
  /**
   * Remove the specified resource from storage.
   *
   * @param int $id          
   *
   * @return \Illuminate\Http\Response
   */
  public function getDestroy($id) {
    $this->_adminUserRepository->getUsersDelete ( $id );
    return redirect ( StringLiterals::ADMIN_USERS )->withSuccess ( trans ( 'user::adminuser.deleted' ) );
  }
  
  /**
   * Method used to delete selected users
   *
   * @return \Illuminate\Http\Response
   */
  public function postAction() {
    $this->_adminUserRepository->getUsersDeleteAll ();
    return redirect ( StringLiterals::ADMIN_USERS )->withSuccess ( trans ( 'adminuser.selected_deleted' ) );
  }
  
  /**
   * Method used to change password
   *
   * @return \Illuminate\Http\View
   */
  public function getChangepassword() { 
    return view ( 'user::admin.changepassword', [ 
        StringLiterals::RULES => $this->_adminUserRepository->setRules ( [ 
            'old_password' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required|same:password' 
        ] )->getRules () 
    ] );
  }
  
  /**
   * Method used to change password
   *
   * @return \Illuminate\Http\View
   */
  public function getProfile() {
    return view ( 'user::admin.profile' );
  }

  /**
   * Method used to update password for the logged in user
   *
   * @return \Illuminate\Http\Response
   */
  public function postChangepassword() {
    if ($this->_adminUserRepository->updatePassword ()) {
      return redirect ( 'users/changepassword' )->withSuccess ( trans ( 'user::adminuser.changepassword.success' ) );
    } else {
      return redirect ( 'users/changepassword' )->withErrors ( trans ( 'user::adminuser.changepassword.incorrect' ) );
    }
  }
  
  /**
   * Method used to update the profile details
   *
   * @return \Illuminate\Http\Response
   */
  public function postProfile() {
    $this->_adminUserRepository->updateProfile ( $this->auth->user ()->id );
    return redirect ( 'users/profile' )->withSuccess ( trans ( 'user::adminuser.profile.success' ) );
  }
  
  /**
   * Check user email is unique
   *
   * @param int $id          
   * @return \Illuminate\Http\Response
   */
  public function getUnique($id = null) {
    return response ()->json ( [ ], $this->_adminUserRepository->isUniqueUserEmail ( $id ) ? 200 : 404 );
  }
  
  /**
   * get Grid template
   *
   * @return \Illuminate\Http\View
   */
  public function getGrid() {
    return view ( 'user::admin.users.grid' );
  }
  /**
   * get Grid template
   *
   * @return \Illuminate\Http\View
   */
  public function getGridlist() {
    return view ( 'user::admin.users.gridView' );
  }
  /**
   * Logout admin login
   *
   * @return \Illuminate\Http\Response
   */
  public function getLogout() {
    $this->auth->user ()->where ( 'id', $this->auth->user ()->id )->update ( [ 
        'last_logged_out_at' => Carbon::now () 
    ] );
    auth()->logout();
        return redirect ( '/' );
    }
}
