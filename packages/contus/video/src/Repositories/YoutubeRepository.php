<?php

/**
 * Youtube Repository
 *
 * To manage the functionalities related to videos
 *
 * @vendor Contus
 *
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 *
 */
namespace Contus\Video\Repositories;

use Contus\Base\Repository as BaseRepository;
use Google_Client;
use Google_Service_YouTube;
use Google_Service_Oauth2;
use Contus\Video\Models\Video;
use Contus\Video\Library\YoutubeDownloaderLibrary;
use Illuminate\Support\Facades\File;
use Contus\Video\Models\TranscodedVideo;
use Contus\Video\Models\LiveScheduler;

class YoutubeRepository extends BaseRepository {
    use YoutubeDownloaderLibrary;
    const APPLICATION_NAME = 'Google Apps Youtube Sync';
    private $clientId;
    private $clientSecret;
    private $credentialPath;
    private $clientSecretPath;
    private $youtube;
    public $client;
    /**
     * Construct method initialization
     *
     * Validation rule for user verification code and forgot password.
     */
    public function __construct() {
        parent::__construct ();
        $this->credentialPath = storage_path ( 'script-php-quickstart.json' );
        $this->clientSecretPath = storage_path ( "client_secret.json" );
        $this->clientId = config ()->get ( 'settings.google.google-general.client_id' );
        $this->clientSecret = config ()->get ( 'settings.google.google-general.client_secret' );
        $this->client = new \Google_Client ();
        $this->client->setApplicationName ( YoutubeRepository::APPLICATION_NAME );
        $this->client->setScopes ( implode ( ' ', [ Google_Service_YouTube::YOUTUBE_READONLY,Google_Service_YouTube::YOUTUBE,Google_Service_YouTube::YOUTUBE_FORCE_SSL,Google_Service_Oauth2::USERINFO_EMAIL ] ) );
        $this->client->setAuthConfig ( $this->clientSecretPath );
        $this->client->setAccessType ( 'offline' );
        $this->client->setApprovalPrompt('force');
        $this->client->setRedirectUri ( url ( 'admin/youtube-import' ) );
    }
    /**
     * function to authenticate the client
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|boolean
     */
    public function authenticate() {
        // Request authorization from the user.
        if ($this->request->has ( 'code' )) {
            $accessToken = $this->client->fetchAccessTokenWithAuthCode ( $this->request->code );
            // Store the credentials to disk.
            if (! file_exists ( dirname ( $this->credentialPath ) )) {
                mkdir ( dirname ( $this->credentialPath ), 0700, true );
            }
            file_put_contents ( $this->credentialPath, json_encode ( $accessToken ) );
            return true;
        } else {
            $authUrl = $this->client->createAuthUrl ();
            header ( 'Location: ' . $authUrl );
            exit ();
        }
        return false;
    }
    /**
     * Returns an authorized API client.
     *
     * @return Google_Client the authorized client object
     */
    private function setClient() {
        try {
            if (file_exists ( $this->credentialPath )) {
                $accessToken = json_decode ( file_get_contents ( $this->credentialPath ), true );
            } else {
                return false;
            }
            $this->client->setAccessToken ( $accessToken );
            // Refresh the token if it's expired.
            if ($this->client->isAccessTokenExpired ()) {
                $this->client->fetchAccessTokenWithRefreshToken ( $this->client->getRefreshToken () );
                file_put_contents ( $this->credentialPath, json_encode ( $this->client->getAccessToken () ) );
            }
            return true;
        }
        catch ( Exception $e ) {
            // The API encountered a problem before the script started executing.
            return false;
        }
    }
    /**
     * Funtion to authenticate for scheduler
     */
    public function authScheduler() {
        $live = LiveScheduler::where ( 'id', 1 )->first ();
        if (!isset ( $live )){
            $live = new LiveScheduler ();
        }
        $live->id = 1;
        $live = LiveScheduler::where ( 'id', 1 )->first ();
        if ($this->setClient ()) {
            $this->youtube = new Google_Service_YouTube ( $this->client );
            $live->status = 1;
            $live->save ();
            $live->touch ();
            return true;
        } else {
            $live->status = 0;
            $live->save ();
        }
        return false;
    }
    /**
     * Funtion to call youtube
     *
     * @param string $type
     */
    public function callYoutube($type = 'getlive', $scheduler = false) {
        try {
            switch ($type) {
                case 'getlive' :
                    $this->youtubeListLiveBroadcast ();
                    break;
                case 'getuploads' :
                    $this->youtubeListUploads ();
                    break;
                default :
                    break;
            }
            session ()->flash ( 'success', 'Videos imported Successfully' );
        }
        catch ( Google_Service_Exception $e ) {
            session ()->flash ( 'error', 'A service error occurred:', htmlspecialchars ( $e->getMessage () ) );
        }
        catch ( Google_Exception $e ) {
            session ()->flash ( 'error', 'A service error occurred:', htmlspecialchars ( $e->getMessage () ) );
        }
    }
    /**
     * Funtion to call youtube List all uploads
     */
    public function youtubeListUploads() {
        // Execute an API request that lists the streams owned by the user who
        // authorized the request.
        $channelsResponse = $this->youtube->channels->listChannels ( 'contentDetails', array ('mine' => 'true' ) );
        foreach ( $channelsResponse ['items'] as $channel ) {
            $this->youtubeListUploadsChannel ( $channel );
        }
    }
    /**
     * List of youtube save all list uploads
     *
     * @param string $channel
     * @param string $nextpage
     */
    public function youtubeListUploadsChannel($channel, $nextpage = '') {
        // Extract the unique playlist ID that identifies the list of videos
        // uploaded to the channel, and then call the playlistItems.list method
        // to retrieve that list.
        $uploadsListId = $channel ['contentDetails'] ['relatedPlaylists'] ['uploads'];
        $streamsResponse = $this->youtube->playlistItems->listPlaylistItems ( 'id,snippet,contentDetails,status', array ('playlistId' => $uploadsListId,'maxResults' => 50,'pageToken' => $nextpage ) );
        foreach ( $streamsResponse->items as $live ) {
            $newLive = Video::where ( [ 'youtube_id' => $live->snippet->resourceId->videoId ] )->first ();
            if (empty ( $newLive ['id'] )) {
                $newLive = new Video ();
                $newLive->job_status = 'Added';
                $newLive->is_active = 1;
                $newLive->creator_id = $this->authUser->id;
                $newLive->updator_id = $this->authUser->id;
            }
            $newLive->youtube_id = $live->snippet->resourceId->videoId;
            $newLive->created_at = date ( "Y-m-d H:i:s", strtotime ( $live->snippet->publishedAt ) );
            $newLive->title = $live->snippet->title;
            $newLive->description = $live->snippet->description;
            $newLive->youtubePrivacy = $live->status->privacyStatus;
            $newLive->video_url = 'https://youtu.be/' . $live->snippet->resourceId->videoId;
            $newLive->short_description = substr ( $live->snippet->description, 0, 200 );
            $newLive->nextPageToken = ($streamsResponse->nextPageToken) ? $streamsResponse->nextPageToken : '';
            $newLive->totalResults = $streamsResponse->pageInfo->totalResults;
            $newLive->save ();
            foreach ( $live->snippet->thumbnails as $thumb ) {
                TranscodedVideo::where ( 'video_id', $newLive->id )->delete ();
                $transcodedThumb = new TranscodedVideo ();
                $transcodedThumb->video_id = $newLive->id;
                $transcodedThumb->thumb_url = $thumb->url;
                $transcodedThumb->is_active = 1;
                $transcodedThumb->save ();
                $newLive->selected_thumb = $thumb->url;
            }
            $newLive->save ();
        }
        if ($streamsResponse->nextPageToken) {
            $this->youtubeListUploadsChannel ( $channel, $streamsResponse->nextPageToken );
        }
    }
    /**
     * List of all youtube live broadcast
     *
     * @param string $nextpage
     */
    public function youtubeListLiveBroadcast($nextpage = '') {
        // Execute an API request that lists the streams owned by the user who
        // authorized the request.
        $streamsResponse = $this->youtube->liveBroadcasts->listLiveBroadcasts ( 'id,snippet,contentDetails,status', array ('broadcastType' => 'all','mine' => 'true','maxResults' => 50,'pageToken' => $nextpage ) );
        foreach ( $streamsResponse->items as $live ) {
            $newLive = Video::where ( [ 'youtube_id' => $live->id ] )->first ();
            if (empty ( $newLive ['id'] )) {
                $newLive = new Video ();
                $newLive->job_status = 'Complete';
                $newLive->creator_id = 1;
                $newLive->updator_id = 1;
            }
            $newLive->youtube_live = 1;
            $newLive->youtube_id = $live->id;
            $newLive->created_at = date ( "Y-m-d H:i:s", strtotime ( $live->snippet->publishedAt ) );
            $newLive->title = $live->snippet->title;
            $newLive->description = $live->snippet->description;
            $newLive->youtubePrivacy = $live->status->privacyStatus;
            if ($newLive->liveStatus != 'complete') {
                $newLive->liveStatus = $live->status->lifeCycleStatus;
                $newLive->video_url = 'https://youtu.be/' . $live->id;
                $newLive->totalResults = $streamsResponse->pageInfo->totalResults;
                $newLive->short_description = substr ( $live->snippet->description, 0, 200 );
                $newLive->nextPageToken = ($streamsResponse->nextPageToken) ? $streamsResponse->nextPageToken : '';
                $scheduledatetime = (date ( "Y-m-d", strtotime ( $live->snippet->scheduledStartTime ) ) != '1970-01-01') ? date ( "Y-m-d H:i:s", strtotime ( $live->snippet->scheduledStartTime ) ) : '';
                $newLive->scheduledStartTime = $scheduledatetime;
                $newLive->save ();
                $img = array (($live->snippet->thumbnails->default) ? $live->snippet->thumbnails->default->url : '',($live->snippet->thumbnails->medium) ? $live->snippet->thumbnails->medium->url : '',($live->snippet->thumbnails->high) ? $live->snippet->thumbnails->high->url : '',($live->snippet->thumbnails->standard) ? $live->snippet->thumbnails->standard->url : '',($live->snippet->thumbnails->maxres) ? $live->snippet->thumbnails->maxres->url : '' );
                foreach ( $img as $thumb ) {
                    TranscodedVideo::where ( 'video_id', $newLive->id )->delete ();
                    if ($thumb) {
                        $transcodedThumb = new TranscodedVideo ();
                        $transcodedThumb->is_active = 1;
                        $transcodedThumb->thumb_url = $thumb;
                        $transcodedThumb->video_id = $newLive->id;
                        $transcodedThumb->save ();
                        $newLive->selected_thumb = $thumb;
                    }
                }
                $newLive->save ();
            }
        }
        if ($streamsResponse->nextPageToken) {
            $this->youtubeListLiveBroadcast ( $streamsResponse->nextPageToken );
        }
    }
    /**
     * funtion to import all to set scheduler
     */
    public function import() {
        $getDYVideos = new Video ();
        $getDYVideos = $getDYVideos->where ( 'youtube_id', '!=', '' )->where ( [ 'youtube_live' => 0,'job_status' => 'Added' ] )->get ();
        foreach ( $getDYVideos as $video ) {
            $my_id = $this->validateVideoId ( $video->youtube_id );
            /* First get the video info page for this video id */
            $my_video_info = 'http://www.youtube.com/get_video_info?&video_id=' . $my_id . '&asv=3&el=detailpage&hl=en_US';
            // video details fix *1
            $my_video_info = $this->curlGet ( $my_video_info );
            $title = $url_encoded_fmt_stream_map = $type = $url = $sig = '';
            parse_str ( $my_video_info );
            $cleanedtitle = $this->clean ( $title );
            if (isset ( $url_encoded_fmt_stream_map )) {
                /* Now get the url_encoded_fmt_stream_map, and explode on comma */
                $my_formats_array = explode ( ',', $url_encoded_fmt_stream_map );
            }
            foreach ( $my_formats_array as $format ) {
                parse_str ( $format );
                $type = explode ( ';', $type );
                $avail_formatsurl = urldecode ( $url ) . '&signature=' . $sig;
                if ($this->get_size ( $avail_formatsurl )) {
                    $video->fine_uploader_uuid = $this->randomCharGen ( 10 );
                    $video->fine_uploader_name = $cleanedtitle . '.mp4';
                    File::makeDirectory ( public_path ( 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $video->fine_uploader_uuid ), 0777, true, true );
                    file_put_contents ( public_path ( 'uploads' . DIRECTORY_SEPARATOR . 'videos' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $video->fine_uploader_uuid . DIRECTORY_SEPARATOR . $video->fine_uploader_name ), fopen ( $avail_formatsurl . '&title=' . $cleanedtitle, 'r' ) );
                    $video->job_status = 'Video Uploaded';
                } else {
                    $video->job_status = 'Error';
                }
                $video->save ();
                break;
            }
        }
    }
}