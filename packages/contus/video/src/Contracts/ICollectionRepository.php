<?php

/**
 * Implements of ICollectionRepository
 *
 * Inteface for implementing the CollectionRepository modules and functions  
 * 
 * @name       ICollectionRepository
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Contracts;

interface ICollectionRepository {
  
  /**
   * Store a newly created collection.
   *
   * @param array $id
   *          input values
   *          
   * @return void
   */
  public function addOrUpdateCollection($id = null);
  
  /**
   * Fetch users to display in collection block.
   *
   * @param array $status
   *          input values
   *          
   * @return response
   */
  public function getCollections($status);
  
  /**
   * Fetch user to edit.
   *
   * @return response
   */
  public function getCollection($id);
}
