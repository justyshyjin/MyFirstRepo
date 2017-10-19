<?php

/**
 * Implements of ICountriesRepository
 *
 * Inteface for implementing the CountriesRepository modules and functions  
 * 
 * @name       ICountriesRepository
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Contracts;

interface ICountriesRepository {

    /**
     * Function to get all countries.
     *
     * @return string The hierarchy string.
     */
    public function getAllCountries();
}