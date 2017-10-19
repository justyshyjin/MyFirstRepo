<?php

/**
 * Implements of IAdminUserGroupRepository
 *
 * Inteface for implementing the AdminUserGroupRepository modules and functions  
 * 
 * @name       IAdminUserGroupRepository
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\User\Contracts;

interface IAdminUserGroupRepository {
  
    /**
     * Get headings for grid
     *
     * @vendor Contus
     * @package User
     * @return array
     */
    public function getGridHeadings();
    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     *
     * @vendor Contus
     * @package User
     * @return Contus\User\Repositories\BaseRepository
     */
    public function prepareGrid();
}