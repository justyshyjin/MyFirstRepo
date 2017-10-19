<?php

/**
 * Aws Billing Model for aws_billing table in database
 *
 * @name       AwsBilling
 * @vendor     Contus
 * @package    Video
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Base\Model;

class AwsBilling extends Model {
   /**
    * The database table used by the model.
    *
    * @vendor Contus
    * 
    * @package Video
    * @var string
    */
   protected $table = 'aws_billing';
   
   /**
    * The attributes that are mass assignable.
    *
    * @vendor Contus
    * 
    * @package Video
    * @var array
    */
   protected $fillable = [ 
       'aws_service',
       'billing_date',
   ];
}

