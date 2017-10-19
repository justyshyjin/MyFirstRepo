<?php

/**
 * Collection Models.
 *
 * @name Collection
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Base\Model;
use Contus\Video\Models\Video;
use Contus\Base\Helpers\StringLiterals;

class Collection extends Model {

    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Video
     * @var string
     */
    protected $table = 'collections';

    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package Video
     * @var array
     */
    protected $fillable = [ 'title',StringLiterals::ISACTIVE,'order' ];
    /**
     * Constructor method
     * sets hidden for notifications
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'is_active','customer_id','creator_id','updated_at','updator_id','id','pivot' ] );
    }

    /**
     * funtion to automate operations while Saving
     */
    public function bootSaving() {
        $this->setDynamicSlug ( 'title' );
        $keysArray = array('dashboard_exams');
        $this->clearCache($keysArray);
    }
    /**
     * Method used to filter the users based on the request.
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $status) {
        if ($status == 'active') {
            $query->where ( StringLiterals::ISACTIVE, 1 );
        } else if ($status == 'in-active') {
            $query->where ( StringLiterals::ISACTIVE, 0 );
        }
        return $query;
    }
    /**
     * belongsToMany relationship between video and collections_videos
     */
    public function videos() {
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            return $this->belongsToMany ( Video::class, 'collections_videos', 'collection_id', 'video_id' )->where ( 'videos.is_active', '1' )->where ( 'videos.job_status', 'Complete' )->where ( 'videos.is_archived', 0 )->whereIn ( 'videos.is_subscription', ((auth ()->user () && auth ()->user ()->isExpires ()) ? [ [ 0 ],[ 1 ] ] : [ 0 ]) )->withTimestamps ();
        } else {
            return $this->belongsToMany ( Video::class, 'collections_videos', 'collection_id', 'video_id' )->withTimestamps ();
        }
    }
    /**
     * belongsToMany relationship between video and collections_videos
     */
    public function categories() {
        return $this->belongsToMany ( Category::class, 'collections_videos', 'collection_id', 'category_id' )->withTimestamps ();
    }
    /**
     * belongsToMany relationship between video and collections_videos
     */
    public function groups() {
        return $this->hasMany( Group::class,'collection_id','id' )->where('is_active',1);
    }
}
