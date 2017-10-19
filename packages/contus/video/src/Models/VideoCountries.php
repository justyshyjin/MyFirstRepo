<?php

/**
 * VideoCountries Models.
 *
 * @name VideoCountries
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Base\Model;
use Contus\Video\Models\Video;
use Contus\Video\Models\Countries;

class VideoCountries extends Model {
    
    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Video
     * @var string
     */
    protected $table = 'video_countries';
    
    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package Video
     * @var array
     */
    protected $fillable = [ 'video_id','country_id' ];
    
    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'id','video_id','country_id','created_at','updated_at' ] );
    }
    /**
     * Belongsto relationship between video_countries and videos
     */
    public function video() {
        return $this->belongsTo ( Video::class, 'video_id' )->select ( 'id', 'title' );
    }
    /**
     * Belongsto relationship between video_countries and countries
     */
    public function country() {
        return $this->belongsTo ( Countries::class, 'country_id' )->select ( 'id', 'name' );
    }
}