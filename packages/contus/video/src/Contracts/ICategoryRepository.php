<?php

/**
 * Implements of ICategoryRepository
 *
 * Inteface for implementing the CategoriesRepository modules and functions  
 * 
 * @name       ICategoryRepository
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Contracts;

interface ICategoryRepository {
  
  /**
   * Store a newly created categories.
   *
   * @param array $id
   *          input values
   *          
   * @return void
   */
  public function addOrUpdateCategory($id = null);
  
  /**
   * Fetch users to display in categories block.
   *
   * @param array $status
   *          input values
   *          
   * @return response
   */
  public function getCategories($status);
  
  /**
   * Fetch user to edit.
   *
   * @return response
   */
  public function getCategory($id);
}
