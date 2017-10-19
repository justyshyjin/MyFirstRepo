<?php

/**
 * Notification Model is used to manage the notifications in database
 *
 * @name Notification
 * @vendor Contus
 * @package notification
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Notification\Models;

use Contus\Base\Model;
use Contus\Customer\Models\Customer;
use Contus\User\Models\User;
use Contus\Video\Models\Video;

class Notification extends Model {

    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package notification
     * @var string
     */
    protected $table = 'notifications';

    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package notification
     * @var array
     */
    protected $fillable = [ 'content','type','type_id','is_read' ];

    /**
     * Constructor method
     * sets hidden for notifications
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'id','customer_id','user_id','creator_id','updated_at','updator_id' ] );
    }
    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function creator() {
        return $this->morphToMany ( Notification::class, 'creator', 'notifications', 'creator_id', 'id' );
    }
    /**
     * Set relation notifications user_id belongs to user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users() {
        return $this->morphedByMany ( User::class, 'creator', 'notifications', 'id', 'creator_id' )->selectRaw ( '*,users.profile_image as profile_picture' );
    }

    /**
     * Set relation notification customer_id belongs to customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customers() {
        return $this->morphedByMany ( Customer::class, 'creator', 'notifications', 'id', 'creator_id' );
    }
    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function type() {
        return $this->morphToMany ( Notification::class, 'type', 'notifications', 'type_id', 'id' );
    }
    /**
     * Set relation notifications user_id belongs to user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function videos() {
        return $this->morphedByMany ( Video::class, 'type', 'notifications', 'id', 'type_id' );
    }
    /**
     * Function used to get the live time for vidoe notifications for mobile
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function video_notification() {
        return $this->morphedByMany ( Video::class, 'type', 'notifications', 'id', 'type_id' )->where('is_active',1)->where('is_archived',0);
    }
}