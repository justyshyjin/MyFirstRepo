<?php

/**
 * Comment Models.
 *
 * @name VideoLike
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Video\Models\Video;
use Contus\User\Models\User;
use Contus\Customer\Models\Customer;
use Contus\Base\Model;

class VideoLike extends Model {

    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Video
     * @var string
     */
    protected $table = 'video_likes';
    /**
     * Hidden variable to be returned
     *
     * @vendor Contus
     *
     * @package Video
     * @var array
     */
    protected $hidden = [ 'video_id','customer_id','updated_at' ];
    /**
     * belongsTo relationship between video and VideoLike
     */
    public function videos() {
        return $this->belongsTo ( Video::class );
    }

    /**
     * Belongs to relationship between customer and VideoLike
     */
    public function customer() {
        return $this->belongsTo ( Customer::class );
    }
}
