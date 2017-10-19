<?php

/**
 * Group Model for Exams table in database
 *
 * @name Group
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

class Group extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'name','slug','is_active','collection_id','group_image','order' ];
    /**
     * The attribute will used to generate url
     *
     * @var array
     */
    protected $url = [ 'group_image' ];

    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'id','creator_id','collection_id','is_active','order','updator_id' ] );
    }

    /**
     * funtion to automate operations while Saving
     */
    public function bootSaving() {
        $this->setDynamicSlug ( 'name' );
        $this->saveImage ( 'group_image' );
        $keysArray = array ('dashboard_exams' );
        $this->clearCache ( [ 'groupList' . $this->slug ] );
        $this->clearCache ( $keysArray );
    }

    /**
     * belongsToMany relationship between video and Exams group
     */
    public function examgroups() {
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            return $this->belongsToMany ( Video::class, 'video_playlists', 'playlist_id', 'video_id' )->where ( 'videos.is_active', '1' )->where ( 'job_status', 'Complete' )->where ( 'is_archived', 0 )->whereIn ( 'is_subscription', ((auth ()->user () && auth ()->user ()->isExpires ()) ? [ [ 0 ],[ 1 ] ] : [ 0 ]) );
        } else {
            return $this->belongsToMany ( Video::class, 'video_playlists', 'playlist_id', 'video_id' )->where ( 'job_status', 'Complete' )->where ( 'is_archived', 0 );
        }
    }
    /**
     * belongsToMany relationship between video and collections_videos
     */
    public function group_videos() {
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            return $this->belongsToMany ( Video::class, 'collections_videos', 'group_id', 'video_id' )->where ( 'videos.is_active', '1' )->where ( 'videos.job_status', 'Complete' )->where ( 'videos.is_archived', 0 )->whereIn ( 'videos.is_subscription', ((auth ()->user () && auth ()->user ()->isExpires ()) ? [ [ 0 ],[ 1 ] ] : [ 0 ]) )->withTimestamps ();
        } else {
            return $this->belongsToMany ( Video::class, 'collections_videos', 'group_id', 'video_id' )->withTimestamps ();
        }
    }
    /**
     * hasOne relationship between exams and groups
     */
    public function exams() {
        return $this->hasOne ( Collection::class, 'id', 'collection_id' );
    }
}
