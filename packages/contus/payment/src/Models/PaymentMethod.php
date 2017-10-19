<?php

/**
 * Payment Method Model is used to manage the payment gatways in database
 *
 * @name PaymentMethod
 * @vendor Contus
 * @package payment
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Payment\Models;

use Contus\Base\Model;
use Contus\Customer\Models\Customer;

class PaymentMethod extends Model {

    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package payment
     * @var string
     */
    protected $table = 'payment_methods';

    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package payment
     * @var array
     */
    protected $fillable = [ 'id','name','type','description','is_active','is_test' ];

    /**
     * Constructor method
     * sets hidden for payment methods
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'id','is_test','is_active','creator_id','updator_id','created_at','updated_at','paymentsettings' ] );
    }
    /**
     * funtion to automate operations while Saving
     */
    public function bootSaving() {
        $this->setDynamicSlug ( 'name' );
    }

    /**
     * HasMany relationship between Payment Methods and payment settings
     */
    public function paymentsettings() {
        return $this->hasMany ( PaymentSetting::class, 'payment_method_id', 'id' );
    }
    /**
     * HasMany relationship between Payment Methods and payment settings
     */
    public function customersettings() {
        return $this->belongsTo ( Customer::class, 'id', 'customer_id' );
    }
}