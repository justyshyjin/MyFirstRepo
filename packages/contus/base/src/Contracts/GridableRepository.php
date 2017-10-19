<?php

/**
 * Implements of GridableRepository
 *
 * 
 * @name       GridableRepository
 * @vendor     Contus
 * @package    Base
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Base\Contracts;

interface GridableRepository {
  /**
   * Prepare the grid
   * set the grid model and relation model to be loaded
   *
   * @vendor     Contus
   * @package    Base
   * @return \Contus\Base\Src\Repositories
   */
  public function prepareGrid();    
  /**
   * Function used to retrieve total number of records
   *
   * @vendor     Contus
   * @package    Base
   * @param string $searchBy          
   * @param string $searchValue          
   * @return int
   */
  public function getTotal();
  /**
   * Function used to retrieve records with field sorting(asc/desc) from database
   *
   * @vendor     Contus
   * @package    Base
   * @param int $startOffset          
   * @param int $endOffset          
   * @param string $fieldName          
   * @param string $sortOrder          
   * @return object records
   */
  public function getRecords();
  /**
   * Get headings for grid
   *
   * @vendor     Contus
   * @package    Base
   * @return array
   */
  public function getGridHeadings();
  /**
   * Act as manager for vaious action performed in the grid
   *
   * @vendor     Contus
   * @package    Base
   * @return boolean
   */
  public function action();
}
