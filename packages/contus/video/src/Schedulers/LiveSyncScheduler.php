<?php

/**
 * Live Sync Scheduler
 *
 * @name LiveSyncScheduler
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Schedulers;

use Contus\Base\Schedulers\Scheduler;
use Contus\Video\Repositories\YoutubeRepository;

class LiveSyncScheduler extends Scheduler {
    public function __construct() {
        parent::__construct ();
        $this->youtube = new YoutubeRepository ();
    }
    /**
     * Scheduler frequency
     *
     * @param \Illuminate\Console\Scheduling\Event $event
     * @return void
     */
    public function frequency(\Illuminate\Console\Scheduling\Event $event) {
        $event->everyTenMinutes ();
    }
    /**
     * Scheduler call method
     * actual execution go's here
     *
     * @return \Closure
     */
    public function call() {
        return function () {
            try{
                app('log')->info('Live Sync Scheduler');
                if ($this->youtube->authScheduler () && $this->youtube->client->getAccessToken ()) {
                    $this->youtube->callYoutube ( 'getlive', true );
                }
            }catch(\Exception $exception){
                app ('log' )->error ( ' ###File : '.$exception->getFile () .' ##Line : '.$exception->getLine () .' #Error : '. $exception->getMessage () );
            }
        };
    }
}