<?php

/**
 * Payment Settings Model is used to manage the payment gatways settings fields in database
 *
 * @name PaymentSettings
 * @vendor Contus
 * @package payment
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Payment\Models;

use Contus\Base\Model;

class PaymentSetting extends Model {

    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package payment
     * @var string
     */
    protected $table = 'payment_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package payment
     * @var array
     */
    protected $fillable = [ 'payment_method_id','key','slug','value','is_test','validation' ];

    /**
     * Constructor method
     * sets hidden for payment methods
     */
    public function __construct() {
        parent::__construct ();
        $this->setVisibleCustomer ( ['key','value'] );
    }
    /**
     * HasMany relationship between Payment settings and payment methods
     */
    public function paymentmethods() {
        return $this->belongsTo ( PaymentMethod::class );
    }
}