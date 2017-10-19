<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Scheduler's
    |--------------------------------------------------------------------------
    |
    | this configuration will have array scheduler class should be executed
    |
    */
    Contus\Video\Schedulers\TranscoderJobStatusScheduler::class,
    Contus\Video\Schedulers\PresetsUpdateScheduler::class,
    Contus\Video\Schedulers\UploadToS3Scheduler::class,
    Contus\Video\Schedulers\AWSStatsScheduler::class,
    Contus\Video\Schedulers\AWSBillingScheduler::class,
    Contus\Customer\Schedulers\SubscriptionScheduler::class,
    Contus\Video\Schedulers\LiveSyncScheduler::class,
    Contus\Video\Schedulers\WowzaStartingStatus::class,
];
