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
use Contus\Customer\Models\Customer;
use Carbon\Carbon;

class Playlist extends Model {
    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Video
     * @var string
     */
    protected $table = 'playlists';

    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package Video
     * @var array
     */
    protected $fillable = [ 'name','is_active','playlist_image' ];

    /**
     * The attribute will used to generate url
     *
     * @var array
     */
    protected $url = [ 'playlist_image' ];

    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'id','category_id','is_active','creator_id','updator_id','pivot' ] );
    }
    /**
     * funtion to automate operations while Saving
     */
    public function bootSaving() {
        $this->setDynamicSlug ( 'name' );
        $this->saveImage ( 'playlist_image' );
        $this->clearCache ( [ 'playlistList' . $this->slug ] );
    }

    /**
     * belongsToMany relationship between video and collections_videos
     */
    public function videos() {
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            return $this->belongsToMany ( Video::class, 'video_playlists', 'playlist_id', 'video_id' )->withTimestamps ()->where ( 'videos.is_active', '1' )->where ( 'job_status', 'Complete' )->where ( 'is_archived', 0 )->whereIn ( 'is_subscription', ((auth ()->user () && auth ()->user ()->isExpires ()) ? [ [ 0 ],[ 1 ] ] : [ 0 ]) );
        } else {
            return $this->belongsToMany ( Video::class, 'video_playlists', 'playlist_id', 'video_id' )->withTimestamps ()->where ( 'job_status', 'Complete' )->where ( 'is_archived', 0 );
        }
    }
    /**
     * belongs to many relationship with count
     */
    public function videosCount() {
        return $this->belongsToMany ( Video::class, 'video_playlists', 'playlist_id', 'video_id' )->where ( 'videos.is_active', '1' )->where ( 'job_status', 'Complete' )->where ( 'is_archived', 0 )->whereIn ( 'is_subscription', ((auth ()->user () && auth ()->user ()->isExpires ()) ? [ [ 0 ],[ 1 ] ] : [ 0 ]) )->selectRaw ( 'count(*) as video_count' )->groupBy ( 'playlist_id' );
    }
    /**
     * belongsToMany relationship between video and collections_videos
     */
    public function category() {
        return $this->belongsTo ( Category::class, 'category_id' );
    }

    /**
     * Method for BelongsToMany relationship between playlist and follow_playlists
     *
     * @vendor Contus
     *
     * @package Customer
     * @return unknown
     */
    public function authFollower() {
        return $this->belongsToMany ( Customer::class, 'follow_playlists' )->where ( 'customer_id', (auth ()->user ()) ? auth ()->user ()->id : 0 )->selectRaw ( 'count(*) as is_follow' )->groupBy ( 'playlist_id' );
    }

    /**
     * Method for BelongsToMany relationship between playlist and follow_playlists
     *
     * @vendor Contus
     *
     * @package Customer
     * @return unknown
     */
    public function followers() {
        return $this->belongsToMany ( Customer::class, 'follow_playlists' )->withPivot ( [ 'created_at' ] );
    }

    /**
     * belongs to many relationship with count
     */
    public function followersCount() {
        return $this->belongsToMany ( Customer::class, 'follow_playlists' )->selectRaw ( 'count(*) as total_followers' )->groupBy ( 'playlist_id' );
    }
    /**
     * Change the updated at column formate
     *
     * @param date $date
     * @return string
     */
    public function getUpdatedAtAttribute($date) {
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            return Carbon::createFromTimeStamp ( strtotime ( $date ) )->diffForHumans ();
        } else {
            return $date;
        }
    }
    /**
     * Change the created at column formate
     *
     * @param date $date
     * @return string
     */
    public function getCreatedAtAttribute($date) {
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            return Carbon::createFromTimeStamp ( strtotime ( $date ) )->diffForHumans ();
        } else {
            return $date;
        }
    }
}

