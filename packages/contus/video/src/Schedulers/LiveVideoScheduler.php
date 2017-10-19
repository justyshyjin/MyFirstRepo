<?php

/**
 * LiveVideo Scheduler
 *
 * @name LiveVideoScheduler
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Schedulers;

use Contus\Base\Schedulers\Scheduler;
use Exception;
use Contus\Video\Repositories\FrontVideoRepository;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Base\Repositories\UploadRepository;
use Contus\Video\Repositories\VideoCountriesRepository;
use Contus\Video\Repositories\VideoCastRepository;
use Contus\Video\Repositories\QuestionsRepository;
use Contus\Video\Repositories\CommentsRepository;

class LiveVideoScheduler extends Scheduler {
    /**
     * Class intializer
     *
     * @return void
     */
    public function __construct(FrontVideoRepository $video) {
        parent::__construct ();
        $this->video = $video;
    }
    /**
     * Scheduler frequency
     *
     * @param \Illuminate\Console\Scheduling\Event $event
     * @return void
     */
    public function frequency(\Illuminate\Console\Scheduling\Event $event) {
        $event->dailyAt('20:00');
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
                app('log')->info('Live Scheduler');
                $this->video->getLiveVideoNotification();
            }catch(\Exception $exception){
                app ('log' )->error ( ' ###File : '.$exception->getFile () .' ##Line : '.$exception->getLine () .' #Error : '. $exception->getMessage () );
            }
        };
    }
}