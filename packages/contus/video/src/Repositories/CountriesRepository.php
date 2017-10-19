<?php

/**
 * Countries Repository
 *
 * To manage the functionalities related to the Countries module from Countries Controller
 * @name       CountriesRepository
 * @vendor Contus
 * @package Countries
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Repositories;

use Contus\Video\Contracts\ICountriesRepository;
use Contus\Video\Models\Countries;
use Contus\Base\Repository as BaseRepository;
use Contus\Base\Repositories\UploadRepository;
use Illuminate\Support\Facades\Hash;
use Contus\Base\Helpers\StringLiterals;

class CountriesRepository extends BaseRepository implements ICountriesRepository {
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_countries;

    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package Video
     * @param Contus\Video\Models\Countries $countries            
     */
    public function __construct(Countries $countries) {
        parent::__construct ();
        $this->_countries = $countries;
    }
    /**
     * Function to get all countries.
     *
     * @return string The hierarchy string.
     */
    public function getAllCountries() {
        return $this->_countries->pluck ( 'name', 'id' );
    }
}