<?php

/**
 * Payment Repository
 *
 * To manage the functionalities related to the Payment module from Payment Controller
 *
 * @name PaymentRepository
 * @vendor Contus
 * @package Payment
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Payment\Repositories;

use Contus\User\Models\User;
use Contus\Base\Repository as BaseRepository;
use Illuminate\Http\Request;
use Contus\Payment\Models\PaymentMethod;
use Contus\Base\Helpers\StringLiterals;
use Contus\Payment\Traits\ccAvenue;
use Contus\Customer\Models\SubscriptionPlan;

class PaymentRepository extends BaseRepository {
    use ccAvenue;

    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_payment;
    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package Payment
     * @param Contus\Payment\Models\Payment $payment
     */
    public function __construct(PaymentMethod $payment) {
        parent::__construct ();
        $this->_payment = $payment;
    }
    /**
     * Store a newly created payment or update the payment.
     *
     * @vendor Contus
     *
     * @package Payment
     * @param $id input
     * @return boolean
     */
    public function updatePayments($id) {
        $payment = $this->_payment->find ( $id );
        if (! is_object ( $payment )) {
            return false;
        }
        $this->setRules ( [ 'name' => 'sometimes|required','type' => 'sometimes|required','description' => 'sometimes|required','is_test' => 'sometimes|required|boolean','is_active' => 'sometimes|required|boolean' ] );
        $payment->updator_id = $this->authUser->id;
        $paymentSet = $payment->paymentsettings ()->where ( 'is_test', $payment->is_test )->get ();
        foreach ( $paymentSet as $paymentsettings ) {
            $this->setRule ( 'setting.' . $paymentsettings->key, $paymentsettings->validation );
            $this->setCustomAttributes ( 'setting.' . $paymentsettings->key, $paymentsettings->key );
        }
        $this->_validate ();
        $payment->fill ( $this->request->except ( '_token' ) );
        if ($payment->save ()) {
            if ($this->request->has ( 'setting' )) {
                foreach ( $paymentSet as $paymentsettings ) {
                    $slug = $paymentsettings->key;
                    if ($this->request->setting [$slug] && ! empty ( $this->request->setting [$slug] )) {
                        $paymentsettings->value = $this->request->setting [$slug];
                        $paymentsettings->updator_id = $this->authUser->id;
                        $paymentsettings->save ();
                    }
                }
            }
            return 1;
        }
    }
    
    
    /**
     * To redirect to various payment options.
     * param request to get filled details
     * return view
     */
    public function postccavRequestHandler($transactionamount,$orderId) {
        
            
        $getPayment = $this->getPayment(2);
        
        $paymentGatway = $getPayment['payment_gateway'];
        $paymentSettings = $getPayment['payment_settings']->where('is_test',$paymentGatway->is_test)->toArray();
        $data = array();
        foreach($paymentSettings as $paymentSetting){
            $data[$paymentSetting['key']] = $paymentSetting['value'];
        }
        $data['order_id'] = $orderId;
        $getMembership = SubscriptionPlan::where('id',$orderId)->first();
        if(count($getMembership)>0){
               $data['merchant_param1']   = $getMembership->name;
        }
        $data['merchant_param2'] = $this->authUser->id;
        $data['amount'] =$transactionamount;
        $data['currency'] = 'INR';
        $data['redirect_url'] = url().'/payment/ccavenueresponseHandler';
        $data['cancel_url'] = url().'/payment/ccavenueresponseHandler';
        $data['language'] = 'EN';
        unset($data['liveURL']);
        unset($data['accessCode']);
        $merchant_data = '';    
        foreach ( $data as $key => $value ) {
            $merchant_data .= $key . '=' . $value . '&';
        } 
     
        return  $this->encrypt ( $merchant_data ,$data['workingKey']);
    }
    
    
    
    /**
     * fetch all the payments with active and settings
     *
     * @vendor Contus
     *
     * @package Payment
     * @return array
     */
    public function getPaymentGateways() {
        return $this->_payment->where ( 'is_active', 1 )->with ( [ 'paymentsettings' => function ($query) {
            return $query->join ( 'payment_methods', function ($join) {
                $join->on ( 'payment_methods.id', '=', 'payment_settings.payment_method_id' )->on ( 'payment_settings.is_test', '=', 'payment_methods.is_test' );
            } );
        } ] )->get ();
    }
    /**
     * fetch all the payments
     *
     * @vendor Contus
     *
     * @package Payment
     * @return array
     */
    public function getAllPayments() {
        return $this->_payment->paginate ( 10 )->toArray ();
    }
    /**
     * fetches one payment
     *
     * @vendor Contus
     *
     * @package Payment
     * @param int $paymentId
     * @return object
     */
    public function getPayment($paymentId) {
        $this->_payment = $this->_payment->find ( $paymentId );
        return [ 'payment_gateway' => $this->_payment,'payment_settings' => $this->_payment->paymentsettings ()->get () ];
    }
    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     * @vendor Contus
     *
     * @package Payment
     * @return Contus\Payment\Repositories\BaseRepository
     */
    public function prepareGrid() {
        $this->setGridModel ( $this->_payment )->setEagerLoadingModels ( [ 'paymentsettings' ] );
        return $this;
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($paymentBuilder) {
        /*
         * updated the all user record only an superadmin user.
         */
        if ($this->authUser->id != 1) {
            $paymentBuilder->where ( 'id', $this->authUser->id )->orWhere ( 'parent_id', $this->authUser->id );
        }
        return $paymentBuilder;
    }

    /**
     * Function to apply filter for search of latestnews grid
     * @vendor Contus
     *
     * @package Payment
     * @param mixed $builderTransaction
     * @return \Illuminate\Database\Eloquent\Builder $builderTransaction The builder object of users grid.
     */
    protected function searchFilter($builderPayment) {
        $searchRecordUsers = $this->request->has ( StringLiterals::SEARCHRECORD ) && is_array ( $this->request->input ( StringLiterals::SEARCHRECORD ) ) ? $this->request->input ( StringLiterals::SEARCHRECORD ) : [ ];

        /**
         * Loop the search fields of users grid and use them to filter search results.
         */
        /**
         * Loop the search fields of users grid and use them to filter search results.
         */

        foreach ( $searchRecordUsers as $key => $value ) {
            if ($key == StringLiterals::ISACTIVE && $value == 'all') {
                continue;
            }

            $builderPayment = $builderPayment->where ( $key, 'like', "%$value%" );
        }

        return $builderPayment;
    }
    /**
     * Get headings for grid
     * @vendor Contus
     *
     * @package Payment
     * @return array
     */
    public function getGridHeadings() {
        return [ StringLiterals::GRIDHEADING => [ [ 'name' => trans ( 'payment::payment.name' ),StringLiterals::VALUE => '','sort' => true ],[ 'name' => trans ( 'payment::payment.type' ),StringLiterals::VALUE => '','sort' => false ],

        [ 'name' => trans ( 'payment::payment.description' ),StringLiterals::VALUE => '','sort' => false ],[ 'name' => trans ( 'payment::payment.is_test' ),StringLiterals::VALUE => '','sort' => true ],[ 'name' => trans ( 'payment::payment.status' ),StringLiterals::VALUE => '','sort' => true ],[ 'name' => trans ( 'payment::payment.actions' ),StringLiterals::VALUE => '','sort' => false ] ] ];
    }
}