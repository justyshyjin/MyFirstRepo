<?php

/**
 * Live Scheduler Models.
 *
 * @name Live Scheduler
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Base\Model;
use Carbon\Carbon;

class LiveScheduler extends Model {

    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Video
     * @var string
     */
    protected $table = 'live_scheduler';

    /**
     * Function to formate created at
     *
     * @param date $date
     * @return string
     */
    public function getUpdatedAtAttribute($date) {
        return Carbon::createFromTimeStamp ( strtotime ( $date ) )->diffForHumans ();
    }
}