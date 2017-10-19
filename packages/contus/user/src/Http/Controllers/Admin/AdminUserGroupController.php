<?php

/**
 * Settings Controller
 *
 * To update the Settings
 *
 * @name       Settings Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\User\Http\Controllers\Admin;

use Contus\User\Repositories\AdminUserGroupRepository;
use Contus\User\Models\SettingCategory;
use Contus\Base\Controller as BaseController;

class AdminUserGroupController extends BaseController {

  /**
   * variable used to redirect
   */  
  protected $redirectToGroup;
  /**
   * Construct method
   */
  public function __construct(AdminUserGroupRepository $adminUserGroupRepository) {
    parent::__construct ();
    $this->_adminUserGroupRepository = $adminUserGroupRepository;
    $this->_adminUserGroupRepository->setRequestType ( static::REQUEST_TYPE );
    $this->redirectToGroup = "admin/groups";
  }
  
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\View
   */
  public function getIndex($status = 'all') {
    return view ( 'user::admin.user_groups.index', [ 
        'users' => $this->_adminUserGroupRepository->getUserGroups ( $status ),
        'status' => $status 
    ] );
  }
   /**
   * get Grid template
   *
   * @return \Illuminate\Http\View
   */
  public function getGridlist() { 
    return view ( 'user::admin.user_groups.gridView' );
  }
  
  /**
   * Show the form for creating a new groups.
   *
   * @return \Illuminate\Http\View
   */
  public function getAdd() {
    return view ( 'user::admin.user_groups.add', [ 
        'rules' => $this->_adminUserGroupRepository->setRule ( 'name', 'required|alpha|unique|max:50' )->getRules () 
    ] );
  }
  /**
   * Store a newly created groups.
   *
   * @return \Illuminate\Http\Response
   */
  public function postAdd() {
    $this->_adminUserGroupRepository->addOrUpdateGroups ();
    return redirect ( $this->redirectToGroup )->withSuccess ( 'Sucessfully Added' );
  }
  /**
   * Show the form for editing the specified resource.
   *
   * @param int $id          
   *
   * @return \Illuminate\Http\View
   */
  public function getEdit($id) {
    return view ( 'user::admin.user_groups.edit', [ 
        'group' => $this->_adminUserGroupRepository->getUserGroup ( $id ),
        'rules' => $this->_adminUserGroupRepository->setRule ( 'name', 'required|alpha|unique|max:50' )->getRules () 
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
    $this->_adminUserGroupRepository->addOrUpdateGroups ( $id );
    return redirect ( $this->redirectToGroup )->withSuccess ( 'Updated Sucessfully' );
  }
  /**
   * Remove the specified resource from storage.
   *
   * @param int $id          
   *
   * @return \Illuminate\Http\Response
   */
  public function getDestroy($id) {
    $this->_adminUserGroupRepository->getGroupsDelete ( $id );
    return redirect ( $this->redirectToGroup )->withSuccess ( trans ( 'Deleted Sucessfully' ) );
  }
  /**
   * Check Group name is unique
   *
   * @param int $id          
   * @return \Illuminate\Http\Response
   */
  public function getUnique($id = null) {
    return response ()->json ( [ ], $this->repository->isUniqueGroupName ( $id ) ? 200 : 404 );
  }
}
