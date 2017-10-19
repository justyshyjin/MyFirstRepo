<?php

/**
 * Implements of IVideoCountriesRepository
 *
 * Inteface for implementing the VideoCountriesRepository modules and functions  
 * 
 * @name       IVideoCountriesRepository
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Contracts;

interface IVideoCountriesRepository {

    /**
     * Function to save video and country mapping.
     *
     * @return string The hierarchy string.
     */
    public function saveVideoCountries($videoId, $countriesList);
}