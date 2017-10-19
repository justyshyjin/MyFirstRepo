<?php
/**
 * AWS Stats Scheduler
 *
 * @name       AWSStatsScheduler
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Schedulers;

use Contus\Base\Schedulers\Scheduler;
use Aws\S3\S3Client;
use Contus\Video\Models\Option;

class AWSStatsScheduler extends Scheduler{
    /**
     * Scheduler frequency
     *
     * @param \Illuminate\Console\Scheduling\Event $event
     * @return void
     */
    public function frequency(\Illuminate\Console\Scheduling\Event $event){
        $event->hourly();
    }
    /**
     * Scheduler call method
     * actual execution go's here
     *
     * @return \Closure
     */
    public function call() {
        return function(){
            $awsS3Bucket = config ()->get ( 'settings.aws-settings.aws-general.aws_s3_bucket' );
            $credentials = array (
                    'region' => config ()->get ( 'settings.aws-settings.aws-general.aws_region' ),
                    'version' => config ( 'contus.video.video.aws_sdk_version' ),
                    'credentials' => [
                    'key' => config ()->get ( 'settings.aws-settings.aws-general.aws_key' ),
                    'secret' => config ()->get ( 'settings.aws-settings.aws-general.aws_secret' )
                    ]
            );
            $awsS3Client = S3Client::factory ( $credentials );

            $size = 0;
            $count = 0;
            $keyMarker = null;
            $params = [
                'Bucket' => $awsS3Bucket
            ];

            do {
                $params['KeyMarker'] = $keyMarker;
                $objects = $awsS3Client->getIterator('ListObjects', $params);

                foreach ($objects as $row) {
                    $count++;
                    $size += $row['Size'];
                }

                $keyMarker = isset($row['NextMarker']) ?: null;
            } while (isset($result['IsTruncated']) && $result['IsTruncated']);

            $size = round($size/1024/1024/1024, 2);
            $formattedSize = $size.' GB';

            $option = Option::firstOrNew(['option_name' => 'bucket_total_objects']);
            $option->option_value = $count;
            $option->option_group = 'aws_stats';
            $option->save();

            $option = Option::firstOrNew(['option_name' => 'bucket_total_space']);
            $option->option_value = $formattedSize;
            $option->option_group = 'aws_stats';
            $option->save();
        };
    }
}