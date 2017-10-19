<?php

/**
 * This is for favourite videos  for favourite_videos table in database
 *
 * @name       favouritevideos
 * @vendor     Contus
 * @package    Video
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Base\Model;

class FavouriteVideo extends Model {
   /**
    * The database table used by the model.
    *
    * @vendor Contus
    * 
    * @package Video
    * @var string
    */
   protected $table = 'favourite_videos';
   
   /**
    * The attributes that are mass assignable.
    *
    * @vendor Contus
    * 
    * @package Video
    * @var array
    */
   protected $fillable = [ 
       'customer_id',
       'video_id',
   ];
}

