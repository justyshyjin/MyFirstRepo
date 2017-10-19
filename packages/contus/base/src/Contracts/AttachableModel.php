<?php

/**
 * Implements of AttachableModel
 *
 * 
 * @name       AttachableModel
 * @vendor     Contus
 * @package    Base
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Base\Contracts;

interface AttachableModel {
    /**
     * Get File Information Model
     * the model related for holding the uploaded file information
     * @vendor     Contus
     * @package    Base
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getFileModel();
}
