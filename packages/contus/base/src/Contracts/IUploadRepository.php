<?php

/**
 * Implements of IUploadRepository
 *
 * Inteface for implementing the IUploadRepository modules and functions  
 * 
 * @name       IUploadRepository
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Base\Contracts;

interface IUploadRepository {
  /**
   * Upload the file to temporary path
   *
   * @return response
   */
  public function tempUpload();
}
