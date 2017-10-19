<?php

/**
 * Upload To S3 Scheduler
 *
 * @name UploadToS3Scheduler
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Schedulers;

use Contus\Base\Schedulers\Scheduler;
use Contus\Video\Repositories\LiveStreamRepository;
use Exception;

class WowzaStartingStatus extends Scheduler {

    public function construct() {
        parent::__construct ();
    }
    /**
     * Scheduler frequency
     *
     * @param \Illuminate\Console\Scheduling\Event $event
     * @return void
     */
    public function frequency(\Illuminate\Console\Scheduling\Event $event) {
        $event->everyThirtyMinutes ();
    }
    /**
     * Scheduler call method
     * actual execution go's here
     *
     * @return \Closure
     */
    public function call() {
        return function () {
                try {
                    (new LiveStreamRepository)->statusLiveStreamAll();
                }
                catch ( Exception $exception ) {
                    app ('log' )->error ( ' ###File : '.$exception->getFile () .' ##Line : '.$exception->getLine () .' #Error : '. $exception->getMessage () );
                }
        };
    }
}