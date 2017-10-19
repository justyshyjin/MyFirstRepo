<?php

/**
 * Trait for Validation
 *
 * @name       validation
 * @vendor     Contus
 * @package    Base
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Base\Handlers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

trait ValidationHandler{
    /**
     * class property to hold the rules for validation
     *
     * @vendor Contus
     * @package Base
     * @var array
     */
    private $rules = [];
    /**
     * class property to hold the custome messages for validation
     *
     * @vendor Contus
     * @package Base
     * @var array
     */
    private $messages = [];  
    /**
     * class property to hold the custom attributes for validation
     *
     * @vendor Contus
     * @package Base
     * @var array
     */
    private $customAttributes = []; 
    /**
     * Get current repository rules
     *
     * @vendor Contus
     * @package Base
     * @return array
     */
    public function getRules() {
        return $this->rules;
    }
    /**
     * Set Rules for the existing repository
     * allow to overwrite the default validation rule
     *
     * @vendor Contus
     * @package Base
     * @param array $rules
     * @return BaseRepository
     */
    public function setRules(array $rules) {
        $this->rules = $rules;
    
        return $this;
    }
    /**
     * Set Rule for the existing repository
     * allow to overwrite the default validation rule for a field
     *
     * @vendor Contus
     * @package Base
     * @param string $field
     * @param string $rule
     * @return BaseRepository
     */
    public function setRule($field,$rule) {
        $this->rules[$field] = $rule;
    
        return $this;
    }
 
    /**
     * Set custom message
     *
     * @vendor Contus
     * @package Base
     * @param string $field
     * @param string $message
     * @return mixed (string | null)
     */
    public function setMessage($field,$message) {
        $this->messages[$field] = $message;
    
        return $this;
    }

    /**
     * Set custom message
     *
     * @vendor Contus
     * @package Base
     * @param string $field
     * @param string $message
     * @return mixed (string | null)
     */
    public function setMessages($field,$message) {
        $this->messages[$field] = $message;
        return $this;
    }
  
    /**
     * Set custom attributes
     *
     * @vendor Contus
     * @package Base
     * @param string $field
     * @param string $name
     * @return mixed (string | null)
     */
    public function setCustomAttributes($field,$name) {
        $this->customAttributes[$field] = $name;
    
        return $this;
    }
    /**
     * Remove Rule for the existing repository
     * allow to remove rule by field
     *
     * @vendor Contus
     * @package Base
     * @param string $field
     * @return BaseRepository
     */
    public function removeRule($field) {
        if(isset($this->rules[$field])){
            unset($this->rules[$field]);
        }
    
        return $this;
    }
    
    /**
     * Remove Rules for the existing repository
     * allow to remove rules by field
     *
     * @vendor Contus
     * @package Base
     * @param string $field
     * @return BaseRepository
     */
    public function removeRules($fields) {
        foreach($fields as $field){
            if(isset($this->rules[$field])){
                unset($this->rules[$field]);
            }
        }
        
        return $this;
    }
    /**
     * Intiate the validation based on the defined rules
     *
     * @vendor Contus
     * @package Base
     * @return void
     * @return BaseRepository
     *
     * @throws \Illuminate\Http\Exception\HttpResponseException
     */
    protected function _validate(){
        $this->validate($this->request,$this->rules,$this->messages,$this->customAttributes);
    
        return $this;
    }    
}
