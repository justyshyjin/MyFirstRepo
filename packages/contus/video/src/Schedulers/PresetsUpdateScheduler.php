<?php

/**
 * Presets Update Scheduler
 *
 * @name PresetsUpdateScheduler
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Schedulers;

use Contus\Base\Schedulers\Scheduler;
use Aws\ElasticTranscoder\ElasticTranscoderClient;
use Contus\Video\Models\VideoPreset;

class PresetsUpdateScheduler extends Scheduler {
    /**
     * Class property to hold Video instance
     *
     * @var \Contus\Video\Models\Video
     */
    protected $videoPreset = null;
    /**
     * Class property to hold AWS instance.
     *
     * @var \Aws\ElasticTranscoder\ElasticTranscoderClient
     */
    public $awsClient;
    /**
     * Class intializer
     *
     * @return void
     */
    public function __construct() {
        parent::__construct ();

        $this->videoPreset = new VideoPreset ();
    }
    /**
     * Scheduler frequency
     *
     * @param \Illuminate\Console\Scheduling\Event $event
     * @return void
     */
    public function frequency(\Illuminate\Console\Scheduling\Event $event) {
        $event->monthly ();
    }
    /**
     * Scheduler call method
     * actual execution go's here
     *
     * @return \Closure
     */
    public function call() {
        return function () {
            $this->awsClient = ElasticTranscoderClient::factory ( array ('region' => config ()->get ( 'settings.aws-settings.aws-general.aws_region' ),'version' => config ( 'contus.video.video.aws_sdk_version' ),'credentials' => [ 'key' => config ()->get ( 'settings.aws-settings.aws-general.aws_key' ),'secret' => config ()->get ( 'settings.aws-settings.aws-general.aws_secret' ) ] ) );
            $this->getAllPresets ();
        };
    }
    /**
     * Function to save presets into the database.
     * This function checks where a preset is already available in the database.
     * If yes, then the preset details are updated and the preset details are inserted if not.
     *
     * @param array $presets
     * The presets returned by AWS SDK.
     */
    public function savePresets($presets) {
        foreach ( $presets as $preset ) {
            /**
             * Check if the current preset is a video preset or not.
             * If it is not a video preset then skip it and save only the video presets in the database.
             */
            if (empty ( $preset ['Video'] )) {
                continue;
            }
            /**
             * Check and insert or update in database.
             */
            $existingPresetId = $this->videoPreset->where ( 'aws_id', $preset ['Id'] )->value ( 'id' );
            if ($existingPresetId) {
                /**
                 * Preset already avaliable.
                 * So update the database.
                 */
                $presetInstance = $this->videoPreset->findOrFail ( $existingPresetId );
            } else {
                $presetInstance = new VideoPreset ();
            }

            $presetInstance->name = $preset ['Name'];
            $presetInstance->description = $preset ['Description'];
            $presetInstance->aws_id = $preset ['Id'];
            $presetInstance->format = $preset ['Container'];
            $presetInstance->thumbnail_format = $preset ['Thumbnails'] ['Format'];
            $presetInstance->save ();
        }
    }
    /**
     * Function to get all the presets from AWS Elastic transcoder.
     *
     * @param string $nextPageToken
     * Optional parameter which is used as a token reference to fetch next set of presets.
     */
    public function getAllPresets($nextPageToken = '') {
        $client = $this->awsClient;

        if (empty ( $nextPageToken )) {
            $result = $client->listPresets ();
        } else {
            $result = $client->listPresets ( array ('PageToken' => $nextPageToken ) );
        }

        $this->savePresets ( $result ['Presets'] );
        if (! empty ( $result ['NextPageToken'] )) {
            /**
             * Call the current function recursively.
             */
            $this->getAllPresets ( $result ['NextPageToken'] );
        }
    }
}