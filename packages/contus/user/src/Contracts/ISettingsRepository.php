<?php

/**
 * Implements of ISettingsRepository
 *
 * Inteface for implementing the SettingsRepository modules and functions  
 * 
 * @name       ISettingsRepository
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\User\Contracts;

interface ISettingsRepository {
  
  /**
   * Fetch settings to display in admin block.
   *
   * @return response
   */
  public function getSettings();
  
  /**
   * Fetch setting categories to display in admin block.
   *
   * @return response
   */
  public function getSettingCategory();
  
  /**
   * Update the settings based on the inputs
   *
   * @return response
   */
  public function updateSettings();
  
  /**
   * Generate cache with settings values
   *
   * @return response
   */
  public function generateSettingsCache();
  
  /**
   * Generate cache with settings values
   *
   * @return response
   */
  public function generateDynamicRules();
}
