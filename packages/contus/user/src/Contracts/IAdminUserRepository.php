<?php

/**
 * Implements of IAdminUserRepository
 *
 * Inteface for implementing the AdminUserRepository modules and functions  
 * 
 * @name       IAdminUserRepository
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\User\Contracts;

interface IAdminUserRepository {
  
  /**
   * Store a newly created user user.
   *
   * @param array $id
   *          input values
   *          
   * @return void
   */
  public function addOrUpdateUsers($id = null);
  
  /**
   * Fetch users to display in admin block.
   *
   * @param array $status
   *          input values
   *          
   * @return response
   */
  public function getUsers($status);
  
  /**
   * Fetch user to edit.
   *
   * @return response
   */
  public function getUser($id);
    
  /**
   * List groups
   *
   * @return response
   */
  public function getGroupsList();
}
