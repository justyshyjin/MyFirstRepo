<?php

/**
 * Payment Controller
* To manage the functionalities related to the Transaction Controller gird api methods
*
* @name Transaction Controller
* @vendor Contus
* @package Payment
* @version 1.0
* @author Contus<developers@contus.in>
* @copyright Copyright (C) 2016 Contus. All rights reserved.
* @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
*/
namespace Contus\Payment\Api\Controllers\Payment;

use Contus\Base\ApiController;
use Contus\Payment\Repositories\PaymentRepository;
use Contus\Customer\Repositories\SubscriptionRepository;
use Contus\Customer\Models\Subscribers;
use Contus\Customer\Models\SubscriptionPlan;
use Contus\Base\Repositories\Config;

class PaymentController extends ApiController {
    /**
     * class property to hold the instance of PaymentRepository
     *
     * @var \Contus\Base\Repositories\SmsTemplatesRepository
     */
    public $paymentRepository;
    /**
     * Construct method
     */
    public function __construct(PaymentRepository $paymentRepository, SubscriptionPlan $subscriptionrepositary) {
        parent::__construct ();
        $this->repository = $paymentRepository;
        $this->subscription = $subscriptionrepositary;
    }

    /**
     * To get the Transaction Controller info.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo() {
        return $this->getSuccessJsonResponse ( [ 'info' => [ 'rules' =>  [ [ 'name' => 'sometimes|required','type' => 'sometimes|required','description' => 'sometimes|required','is_test' => 'sometimes|required|boolean','is_active' => 'sometimes|required|boolean' ] ] ,'allPayments' => $this->repository->getAllPayments () ] ] );
    }
    /**
     * Store a newly created payment.
     *
     * @return \Illuminate\Http\Response
     */
    public function postAdd() {
        $isCreated = false;

        if ($this->repository->updatePayments ()) {
            $isCreated = true;
            $this->request->session ()->flash ( StringLiterals::SUCCESS, trans ( 'payment::payment.add.success' ) );
        }
        return ($isCreated) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::emailtemplate.add.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'payment::payment.add.error' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function postEdit($paymentId) {
        $isCreated = false;
        if ($this->repository->updatePayments ( $paymentId )) {
            $isCreated = true;
            $this->request->session ()->flash ( 'success', trans ( 'payment::payment.update.success' ) );
        }
        return ($isCreated) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'payment::payment.update.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'payment::payment.update.error' ) );
    }

    /**
     * Rsa response key
     */
    public function getRsaresponse() {
        $getPayment = $this->repository->getPayment(2);
        $paymentGatway = $getPayment['payment_gateway'];
        $paymentSettings = $getPayment['payment_settings']->where('is_test',$paymentGatway->is_test)->toArray();
        $data = array();
        foreach($paymentSettings as $paymentSetting){
            $data[$paymentSetting['key']] = $paymentSetting['value'];
        }
        $accessCode = $data['accessCode'];
        $url = $data['liveURL'];
        $merchant_id = $data['merchant_id'];
        $package = $this->subscription->where ( 'id', $this->request->subscription_id )->count ();
        if ($package) {
            $orderId = $this->request->subscription_id;
            $fields = array ('access_code' => $accessCode,'order_id' => $orderId );
            $postvars = '';
            $sep = '';
            foreach ( $fields as $key => $value ) {
                $postvars .= $sep . urlencode ( $key ) . '=' . urlencode ( $value );
                $sep = '&';
            }
            $ch = curl_init ();
            curl_setopt ( $ch, CURLOPT_URL, $url);
            curl_setopt ( $ch, CURLOPT_POST, count ( $fields ) );
            curl_setopt ( $ch, CURLOPT_CAINFO, url ( './cacert.pem' ) );
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, $postvars );
            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
            $response = curl_exec ( $ch );
            $url = url();
            $url = ($url == 'http://admin.learningspacedigital.com')?'http://www.learningspacedigital.com':$url;
            return ($response) ? $this->getSuccessJsonResponse ( [ 'response'=>['rsakey' => $response,'redirecturl' => $url.'/payment/ccavenueresponseHandlerAPP','cancelurl' =>$url.'/payment/ccavenueresponseHandlerAPP','order_id' => $orderId,'merchant_id' =>$merchant_id,'access_code' =>$accessCode],'message' => trans ( 'payment::payment.update.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'payment::payment.update.error' ) );
        }
    }
}