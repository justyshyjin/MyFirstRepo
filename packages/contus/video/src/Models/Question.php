<?php

/**
 * Question Models.
 *
 * @name questionanswer
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Video\Models\Answer;
use Contus\Video\Models\Video;
use Contus\User\Models\User;
use Contus\Customer\Models\Customer;
use Contus\Base\Model;
use Carbon\Carbon;

class Question extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'video_questionanswers';
    /**
     * Hidden variable to be returned
     *
     * @vendor Contus
     *
     * @package Video
     * @var array
     */
    protected $hidden = [ 'video_id','user_id','customer_id','creator_id','updator_id','updated_at' ];
    /**
     * belongsTo relationship between video and questions
     */
    public function videos() {
        return $this->belongsTo ( Video::class );
    }
    public function bootSaving() {
        $keys = array('questions');
        $this->clearCache($keys);
    }
    /**
     * HasMany relationship between videos and reply answers
     */

    public function ReplyAnswer() {
        return $this->hasMany ( Answer::class );
    }

    /**
     * Belongs to relationship between user and comments
     */
    public function admin() {
        return $this->belongsTo ( User::class , 'user_id' )->select ( 'id', 'name','profile_image as profile_picture' );
    }

    /**
     * Belongs to relationship between customer and comments
     */
    public function customer() {
        return $this->belongsTo ( Customer::class );
    }

    /**
     * Belongs to relationship between video and question
     */
    public function video() {
        return $this->belongsTo ( Video::class );
    }
    /**
     * Function to formate created at
     *
     * @param date $date
     * @return string
     */
    public function getCreatedAtAttribute($date) {
        if (app( 'request' )->header ( 'x-request-type' ) == 'mobile') {
            return Carbon::createFromTimeStamp ( strtotime ( $date ) )->diffForHumans ();
        }
        return $date;
    }
}
