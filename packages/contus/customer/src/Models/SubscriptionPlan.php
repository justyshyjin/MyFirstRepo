<?php

namespace Contus\Customer\Models;

use Contus\Base\Model;

class SubscriptionPlan extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'subscription_plans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'name','type','amount','description','duration','is_active' ];

    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'id','is_active','creator_id','updator_id','created_at','updated_at' ] );
    }

    /**
     * funtion to automate operations while Saving
     */
    public function bootSaving() {
        $this->setDynamicSlug ( 'name' );
    }
    /**
     * Belongs to many relation with customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function SubscriberInfo() {
        return $this->belongsToMany ( Customer::class, 'subscribers' );
    }
}
