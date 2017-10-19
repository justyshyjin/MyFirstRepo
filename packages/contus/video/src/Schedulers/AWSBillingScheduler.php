<?php
/**
 * AWS Billing Scheduler
 *
 * @name       AWSBillingScheduler
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Schedulers;

use Contus\Base\Schedulers\Scheduler;
use Aws\S3\S3Client;
use Contus\Video\Models\AwsBilling;
use Contus\Video\Models\AwsMonthWiseBilling;

class AWSBillingScheduler extends Scheduler{
    /**
     * Scheduler frequency
     *
     * @param \Illuminate\Console\Scheduling\Event $event
     * @return void
     */
    public function frequency(\Illuminate\Console\Scheduling\Event $event){
        $event->daily();
    }
    /**
     * Scheduler call method
     * actual execution go's here
     *
     * @return \Closure
     */
    public function call() {
        return function(){
            $folderPath = base_path('storage'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'tempcsv');
            if(!file_exists($folderPath)) {
                mkdir($folderPath);
            }

            $payerAccountId = config ()->get ( 'settings.aws-settings.aws-general.aws_payer_account_id' );
            $currentYear = date('Y');
            $currentMonth = date('m');
            $awsS3BillingBucket = config ()->get ( 'settings.aws-settings.aws-general.aws_billing_s3_bucket' );

            $credentials = array (
                    'region' => config ()->get ( 'settings.aws-settings.aws-general.aws_region' ),
                    'version' => config ( 'contus.video.video.aws_sdk_version' ),
                    'credentials' => [
                            'key' => config ()->get ( 'settings.aws-settings.aws-general.aws_key' ),
                            'secret' => config ()->get ( 'settings.aws-settings.aws-general.aws_secret' )
                    ]
            );
            $awsS3Client = S3Client::factory ( $credentials );
            $awsS3Client->getObject(array(
                    'Bucket' => $awsS3BillingBucket,
                    'Key'    => $payerAccountId.'-aws-cost-allocation-AISPL-'.$currentYear.'-'.$currentMonth.'.csv',
                    'SaveAs' => $folderPath.DIRECTORY_SEPARATOR.'aws-billing.csv'
            ));

            $s3Cost = 0;
            $elasticTranscoderCost = 0;
            $handle = fopen($folderPath.DIRECTORY_SEPARATOR.'aws-billing.csv', "r");
            while (($data = fgetcsv($handle, 0, ",")) !== FALSE)
            {
                if(isset($data[12]) && $data[12] == 'AmazonS3') {
                    $s3Cost += $data[29];
                }
                if(isset($data[12]) && $data[12] == 'AmazonETS') {
                    $elasticTranscoderCost += $data[29];
                }
            }

            $awsBillingInstance = AwsBilling::firstOrNew(['aws_service' => 'aws_s3', 'billing_date' => date('Y-m-d')]);
            $awsBillingInstance->total_cost = $s3Cost;
            $awsBillingInstance->save();

            $awsBillingInstance = AwsBilling::firstOrNew(['aws_service' => 'aws_et', 'billing_date' => date('Y-m-d')]);
            $awsBillingInstance->total_cost = $elasticTranscoderCost;
            $awsBillingInstance->save();

            $awsMonthBilling = AwsMonthWiseBilling::firstOrNew(['billing_year' => date('Y'), 'billing_month' => date('m')]);
            $awsMonthBilling->total_cost = $s3Cost+$elasticTranscoderCost;
            $awsMonthBilling->save();
        };
    }
}