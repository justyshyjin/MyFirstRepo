<?php

namespace Contus\Customer\Models;

use Illuminate\Auth\Authenticatable;
use Contus\Base\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Contus\Base\Contracts\AttachableModel as AttachableModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Contus\Video\Models\Video;
use Contus\Video\Models\Playlist;
use Contus\Video\Models\Collection;

class Customer extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, AttachableModel {
    use Authenticatable, Authorizable, CanResetPassword,SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customers';
    /**
     * Morph class name
     *
     * @var string
     */
    protected $morphClass = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','email','phone','acesstype','is_active','device_token','device_type','profile_picture','age' ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [ 'password' ];
    /**
     * The attribute will used to generate url
     *
     * @var array
     */
    protected $url = ['profile_picture'];

    /**
     * Tthe attributes used for soft delete
     */
    protected $dates = [ 'deleted_at' ];

    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'id','password','access_otp_token','access_token','acesstype','created_at','creator_id','expires_at','facebook_auth_id','facebook_user_id','google_auth_id','google_user_id','is_active','login_type','remember_token','updated_at','updator_id','deleted_at','device_token','device_type','forgot_password','mypreferences','pivot','notify_comment','notification_status','notify_newsletter','notify_reply_comment','notify_videos' ] );
    }
    /**
     * funtion to automate operations while Saving
     */
    public function bootSaving() {
        $this->saveImage ( 'profile_picture','required|mimes:jpeg,png,gif|image|max:5000' );
    }
    /**
     * Get File Information Model
     * the model related for holding the uploaded file information
     *
     * @vendor Contus
     *
     * @package Customer
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getFileModel() {
        return $this;
    }

    /**
     * Check if the current user is a subscribed user or not
     *
     * @vendor Contus
     *
     * @package Customer
     * @return boolean|date
     */
    public function isExpires() {
        $subscription = $this->activeSubscriber ()->first ();
        if (! isset ( $subscription ) && is_null ( $this->expires_at )) {
            return false;
        }
        return $subscription->pivot->end_date;
    }
    /**
     * Method to retrive subscription information with belongsToMany relations
     *
     * @vendor Contus
     *
     * @package Customer
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function Subscriber() {
        return $this->belongsToMany ( SubscriptionPlan::class, 'subscribers' )->withPivot ( [ 'subscription_plan_id','customer_id','start_date','end_date','is_active' ] );
    }
    /**
     * Method to retrive subscription information with belongsToMany relations with only active subscription plan with start and end date
     *
     * @vendor Contus
     *
     * @package Customer
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany with active subscription plan
     */
    public function activeSubscriber() {
        return $this->belongsToMany ( SubscriptionPlan::class, 'subscribers' )->withPivot ( [ 'subscription_plan_id','start_date','end_date','is_active' ] )->where ( 'subscribers.is_active', 1 )->orderBy ( 'subscribers.id', 'desc' );
    }

    /**
     * Method for BelongsToMany relationship between video and favourite_videos
     *
     * @vendor Contus
     *
     * @package Customer
     * @return unknown
     */
    public function favourites() {
        return $this->belongsToMany ( Video::class, 'favourite_videos', 'customer_id', 'video_id' )->where ( 'videos.is_active', '1' )->where ( 'videos.job_status', 'Complete' )->where ( 'videos.is_archived', 0 )->withTimestamps ()->selectRaw ( 'videos.*,favourite_videos.created_at as favourite_created_at' )->orderBy ( 'favourite_videos.id', 'desc' );
    }
    /**
     * Method for BelongsToMany relationship between video and favourite_videos
     *
     * @vendor Contus
     *
     * @package Customer
     * @return unknown
     */
    public function followers() {
        return $this->belongsToMany ( Playlist::class, 'follow_playlists', 'customer_id', 'playlist_id' )->withTimestamps ()->selectRaw ( 'playlists.*,follow_playlists.created_at as follow_created_at' )->orderBy ( 'follow_playlists.id', 'desc' );
    }

    /**
     * Method for BelongsToMany relationship between video and favourite_videos
     *
     * @vendor Contus
     *
     * @package Customer
     * @return unknown
     */
    public function recentlyViewed() {
        return $this->belongsToMany ( Video::class, 'recently_viewed_videos', 'customer_id', 'video_id' )->withTimestamps ()->selectRaw ( 'videos.*,recently_viewed_videos.created_at as recent_created_at' )->orderBy ( 'recently_viewed_videos.id', 'desc' );
    }
    /**
     * Method for BelongsToMany relationship between Customer and collection
     *
     * @vendor Contus
     *
     * @package Customer
     * @return unknown
     */
    public function exams() {
        return $this->belongsToMany ( Collection::class, 'customer_collections', 'customer_id', 'collection_id' )->withTimestamps ();
    }
}
