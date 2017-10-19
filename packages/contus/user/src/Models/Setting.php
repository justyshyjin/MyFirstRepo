<?php

/**
 * Settings
 *
 * To manage the functionalities related to settings
 * @name       Settings
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\User\Models;

use Mara\Contracts\ConfigurableModel;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Model;

class Setting extends Model {
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'settings';
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [ 
      'setting_name',
      'setting_value',
      'display_name',
      'type',
      'option',
      'class',
      'order',
      'setting_category_id',
      'description' 
  ];
  
  /**
   * Method used to get the option list based on the settings class
   *
   * @return array
   */
  public function getOption() {
    if ($this->class && ($classInstance = $this->getClassInstance ())) {
      return $classInstance->getOptionList ();
    } else {
      return explode ( ",", $this->option );
    }
  }
  
  /**
   * Method used to create instance for the class updated in settings
   *
   * @return Object
   */
  public function getClassInstance() {
    $classInstance = false;
    
    if (class_exists ( $this->class )) {
      $classInstance = new $this->class ();
      
      $classInstance = ($classInstance instanceof ConfigurableModel) ? $classInstance : StringLiterals::LITERALFALSE;
    }
    
    return $classInstance;
    }
}