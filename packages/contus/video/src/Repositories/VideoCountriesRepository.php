<?php

/**
 * VideoCountriesRepository
 *
 * To manage the functionalities related to the VideoCountries module from Countries Controller
 * @name       VideoCountriesRepository
 * @vendor Contus
 * @package video
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Repositories;

use Contus\Video\Contracts\IVideoCountriesRepository;
use Contus\Video\Models\Countries;
use Contus\Video\Models\VideoCountries;
use Contus\Base\Repository as BaseRepository;
use Contus\Base\Repositories\UploadRepository;
use Illuminate\Support\Facades\Hash;
use Contus\Base\Helpers\StringLiterals;

class VideoCountriesRepository extends BaseRepository implements IVideoCountriesRepository {
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_videoCountries;

    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package Video
     * @param Contus\Video\Models\Countries $countries            
     */
    public function __construct(VideoCountries $videoCountries) {
        parent::__construct ();
        $this->_videoCountries = $videoCountries;
    }
    /**
     * Function to save countries and videos map data.
     *
     * @return string The hierarchy string.
     */
    public function saveVideoCountries($videoId, $countriesList) {

        $this->_videoCountries->where('video_id', $videoId)->delete();
        foreach ($countriesList as $value) {
            # code...
            $videoCountries = new VideoCountries();
            $videoCountries->video_id = $videoId;
            $videoCountries->country_id = $value;
            $videoCountries->save();  
        }
    }

    /**
     * Function to get countryid by using video_id.
     *
     * @return string The hierarchy string.
     */
    public function getCountryIdByVideoId($videoId) {
        return $this->_videoCountries->where('video_id', $videoId)->get()->pluck( 'country_id' );
    }
}