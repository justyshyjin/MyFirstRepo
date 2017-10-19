<?php

/**
 * Video Model for videos table in database
 *
 * @name Video
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Base\Model;
use Contus\Video\Models\TranscodedVideo;
use Contus\Base\Contracts\AttachableModel;
use Symfony\Component\HttpFoundation\File\File;
use Contus\Video\Models\VideoCategory;
use Contus\Base\Helpers\StringLiterals;
use Contus\Video\Models\VideoCountries;
use Contus\Video\Models\VideoPoster;
use Contus\Video\Models\Comment;
use Contus\video\Models\VideoRelation;
use Contus\Customer\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Video extends Model implements AttachableModel {
    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Video
     * @var string
     */
    protected $table = 'videos';
    /**
     * Morph class name
     *
     * @var string
     */
    protected $morphClass = 'videos';

    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package Video
     * @var array
     */
    protected $fillable = [ 'id','category_id','title','description','slug','short_description','country_id','is_featured','is_subscription','is_active','pdf','video_url','is_featured_time','published_on','hls_playlist_url','thumbnail_image'];

    /**
     * The attributes added from the model while fetching.
     *
     * @var array
     */
    protected $appends = [ 'is_demo' ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [ ];
    /**
     * The attribute will used to generate url
     *
     * @var array
     */
    protected $url = [ 'thumbnail_image','selected_thumb' ];
    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( ['notification_status','aws_prefix','is_hls','pipeline_id','preview_image','subscription','job_id','job_status','country_id','is_subscription','is_featured','trailer','disclaimer','subtitle_path','subtitle','thumbnail_path','is_active','creator_id','updator_id','updated_at','is_archived','archived_on','fine_uploader_uuid','fine_uploader_name','youtubePrivacy','liveStatus','pivot','youtube_live','youtube_id','nextPageToken','totalResults' ] );
    }

    /**
     * funtion to automate operations while Saving
     */
    public function bootSaving() {
        $this->setDynamicSlug ( 'title' );
        $this->saveImage ( 'pdf' );
        $this->saveImage ( 'word' );
        $keys = array ('dashboard_categorynave','category_listing_page','dashboard_categories','dashboard_videos','category_live','category_tags','dashboard_live','dashboard_trending','dashboard_video_count','dashboard_pdf_count','dashboard_audio_count','youtube_live' );
        $this->clearCache ( $keys );
    }
    /**
     * HasMany relationship between videos and transcoded_videos
     */
    public function transcodedvideos() {
        return $this->hasMany ( TranscodedVideo::class );
    }
    /**
     * Funtion to append the demo feature in video listing page and detail page
     *
     * @return boolean
     */
    public function getIsDemoAttribute() {
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            return (auth ()->user () && auth ()->user ()->isExpires ()) ? 0 : 1;
        }
    }

    /**
     * HasMany relationship between videos and video_categories
     */
    public function videocategory() {
        return $this->hasMany ( VideoCategory::class );
    }

    /**
     * HasMany relationship between videos and video_countries
     */
    public function comments() {
        return $this->hasMany ( Comment::class );
    }
    public function likes() {
        return $this->hasMany ( VideoLike::class );
    }
    public function likescount() {
        return $this->hasMany ( VideoLike::class )->sum('like_count');
    }
    public function dislikescount() {
        return $this->hasMany ( VideoLike::class )->sum('dislike_count');
    }
    public function likestatus(){
        return $this->belongsTo ( VideoLike::class );
    }
    public function watchlater(){
        return $this->belongsTo ( WatchLater::class );
    }

    /**
     * belongsToMany relationship between collection and collections_videos
     */
    public function collections() {
        return $this->belongsToMany ( Group::class, 'collections_videos', StringLiterals::VIDEOID, 'group_id' )->withTimestamps ();
    }

    /**
     * belongsToMany relationship between collection and collections_videos
     */
    public function playlists() {
        return $this->belongsToMany ( Playlist::class, 'video_playlists', StringLiterals::VIDEOID, 'playlist_id' );
    }

    /**
     * belongsToMany relationship between tag and video_tag
     */
    public function tags() {
        return $this->belongsToMany ( tag::class, 'video_tag', StringLiterals::VIDEOID, 'tag_id' );
    }

    /**
     * belongsToMany relationship between categories and video_categories
     */
    public function categories() {
        return $this->belongsToMany ( Category::class, 'video_categories', StringLiterals::VIDEOID, 'category_id' );
    }
    /**
     * Method for BelongsToMany relationship between video and favourite_videos
     *
     * @vendor Contus
     *
     * @package Customer
     * @return unknown
     */
    public function authfavourites() {
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            return $this->belongsToMany ( Customer::class, 'favourite_videos' )->where ( 'customer_id', (auth ()->user ()) ? auth ()->user ()->id : 0 )->selectRaw ( 'IF(count(*)>0,count(*),0) as favourite  , favourite_videos.created_at as favourite_created_at' )->groupBy ( 'favourite_videos.video_id' );
        } else {
            return $this->belongsToMany ( Customer::class, 'favourite_videos' );
        }
    }
    /**
     * Get File Information Model
     * the model related for holding the uploaded file information
     *
     * @vendor Contus
     *
     * @package Base
     * @return Contus\Base\Model\Video
     */
    public function getFileModel() {
        return $this;
    }
    /**
     * Set the file to Staplaer
     *
     * @param \Symfony\Component\HttpFoundation\File\File $file
     * @param string $config
     * @return void
     */
    public function setFile(File $file, $config) {
        if (isset ( $config->image_resolution )) {
            $this->thumbnail_image = url ( "$config->storage_path/" . $file->getFilename () );
            $this->thumbnail_path = $file->getPathname ();
        }
        if (isset ( $config->is_file )) {
            $this->mp3 = url ( "$config->storage_path/" . $file->getFilename () );
            $this->subtitle_path = $file->getPathname ();
        }

        return $this;
    }
    /**
     * Store the file information to database
     * if attachment model is already has record will update
     *
     * @param Contus\Video\Models\Video $video
     * @return boolean
     */
    public function upload(Video $video) {
        return $video->save ();
    }

    /**
     * HasMany relationship between videos and Video_questions
     */
    public function questions() {
        return $this->hasMany ( Question::class );
    }

    /**
     * Set explicit model condition for fronend
     *
     * {@inheritdoc}
     *
     * @see \Contus\Base\Model::whereCustomer()
     *
     * @return object
     */
    public function whereCustomer() {
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            return $this->where ( 'videos.is_active', '1' )->where ( 'job_status', 'Complete' )->where ( 'is_archived', 0 )->whereIn ( 'is_subscription', ((auth ()->user () && auth ()->user ()->isExpires ()) ? [ [ 0 ],[ 1 ] ] : [ 0 ]) );
        } else {
            return $this->where ( 'job_status', 'Complete' )->where ( 'is_archived', 0 );
        }
    }

    /**
     * Set explicit model condition for mobile
     *
     * {@inheritdoc}
     *
     * @see \Contus\Base\Model::whereliveVideo()
     *
     * @return object
     */
    public function whereliveVideo() {
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            return $this->where ( 'is_active', '1' )->where ( 'job_status', 'Complete' )->where ( 'is_archived', 0 )->where ( 'youtube_live', 1 )->where ( 'liveStatus', '!=', 'complete' )->whereRaw ( 'scheduledStartTime > "' . Carbon::now ()->toDateString () . ' 00:00:00 "' );
        }
    }
    /**
     * Get the scheduled as well as recorded live video lists
     */
    public function whereallliveVideo() {
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            return $this->where ( 'is_active', '1' )->where ( 'job_status', 'Complete' )->where ( 'is_archived', 0 )->where ( 'youtube_live', 1 )->where ( 'liveStatus', '!=', 'complete' )->whereRaw ( 'scheduledStartTime > "' . Carbon::now ()->toDateString () . ' 00:00:00 "' );
        }
    }
    /**
     * This function used to get the recorded live videos
     */
    public function whereRecordedliveVideo() {
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            return $this->where ( 'is_active', '1' )->where ( 'job_status', 'Complete' )->where ( 'is_archived', 0 )->where ( 'youtube_live', 1 )->where ( 'liveStatus', '!=', 'complete' )->whereRaw ( 'scheduledStartTime > "' . Carbon::now ()->toDateString () . ' 00:00:00 "' );
        }
    }
    /**
     * HasMany relationship between videos and video_posters
     */
    public function recent() {
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            return $this->belongsTo ( Customer::class )->where ( 'customers.id', auth ()->user ()->id );
        } else {
            return $this->belongsToMany ( Customer::class, 'recently_viewed_videos' );
        }
    }

}
