<?php

namespace Contus\Customer\Models;

use Contus\Base\Model;

class Subscribers extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'subscribers';

    /**
     * Belongs to many relation with subscription plan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscriptionplan() {
      return $this->belongsTo( SubscriptionPlan::class, 'subscription_plan_id','id' );
    }

}
