<?php

/**
 * Payment Controller
*
* To manage the LatestNews such as create, edit and delete the admin users
*
* @name Payment Controller
* @version 1.0
* @author Contus Team <developers@contus.in>
* @copyright Copyright (C) 2016 Contus. All rights reserved.
* @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
*/
namespace Contus\Payment\Http\Controllers\Customer;

use Contus\Base\Controller as BaseController;
use Carbon\Carbon;
use Contus\Payment\Repositories\PaymentRepository;
use Illuminate\Http\Request;
use Contus\Customer\Models\Customer;
use Contus\Payment\Repositories\TransactionRepository;
use Contus\Payment\Models\PaymentTransactions;

class PaymentController extends BaseController {
    /**
     * Construct method
     */
    public function __construct(PaymentRepository $paymentRepository, TransactionRepository $transactionHistory) {
        parent::__construct ();
        $this->_paymentRepository = $paymentRepository;
        $this->_transactionRepository = $transactionHistory;
        $this->_paymentRepository->setRequestType ( static::REQUEST_TYPE );
    }
    /**
     * To redirect success page
     *
     * @return view
     */
    public function getPaymentSuccess($transaction_id) {
        if(!empty($transaction_id)){
            $getTransactiondetails = PaymentTransactions::where('id',$transaction_id)->first();
            if(count($getTransactiondetails)>0){
                return view ( 'payment::customer.payment.success' )->with('getTransactiondetails',$getTransactiondetails);
            }else{
                return abort(403, 'Unauthorized action.');
            }

        }

    }
    /**
     * To redirect cacel page
     *
     * @return view
     */
    public function getPaymentFailure($transaction_id) {
        if(!empty($transaction_id)){
            $getTransactiondetails = PaymentTransactions::where('id',$transaction_id)->first();
            if(count($getTransactiondetails)>0){
                return view ( 'payment::customer.payment.failed' )->with('getTransactiondetails',$getTransactiondetails);
            }else{
                return abort(403, 'Unauthorized action.');
            }

        }
    }
    /**
     * redirect to  cancel page
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function getPaymentCancel($transaction_id) {
        if(!empty($transaction_id)){
            $getTransactiondetails = PaymentTransactions::where('id',$transaction_id)->first();
            if(count($getTransactiondetails)>0){
                return view ( 'payment::customer.payment.cancel' )->with('getTransactiondetails',$getTransactiondetails);
            }else{
                return abort(403, 'Unauthorized action.');
            }

        }
    }
    /**
     * To return ccavenue response
     * param request to get response
     * return view
     */
    public function ccavenueresponseHandler() {
        $getPayment = $this->_paymentRepository->getPayment(2);
        $paymentGatway = $getPayment['payment_gateway'];
        $paymentSettings = $getPayment['payment_settings']->where('is_test',$paymentGatway->is_test)->toArray();
        $getKeys = array();
        foreach($paymentSettings as $paymentSetting){
            $getKeys[$paymentSetting['key']] = $paymentSetting['value'];
        }
        $workingKey = $getKeys['workingKey'];
        $encResponse = $this->request->encResp;
        $rcvdStrings = $this->_paymentRepository->decrypt ( $encResponse, $workingKey );
        $order_status = "";
        $decryptValues = explode ( '&', $rcvdStrings );
        $dataSize = sizeof ( $decryptValues );
        for($i = 0; $i < $dataSize; $i ++) {
            $information = explode ( '=', $decryptValues [$i] );
            if ($i == 3) {
                $order_status = $information [1];
            }
        }
        $user = explode ( '=', $decryptValues [27] );
        $user_id = $user [1];
        $user = Customer::where ( 'id', $user_id )->first ();
        $subscription = explode ( '=', $decryptValues [0] );
        $subscription_id = $subscription [1];
        if ($order_status === "Success") {
            $saveTransaction = $this->_transactionRepository->addTransactions($subscription_id, $user,$decryptValues);
            if($saveTransaction){
                return redirect(url().'/paymentsuccess/'.$saveTransaction->id);
            }
        } else {
            $saveTransaction = $this->_transactionRepository->addTransactions($subscription_id, $user,$decryptValues);
            return redirect(url().'/paymentfailure/'.$saveTransaction->id);
        }
    }

    /**
     * To return ccavenue response
     * param request to get response
     * return view
     */
    public function ccavenueresponseHandlerAPP() {
        $getPayments = $this->_paymentRepository->getPayment(2);
        $paymentGatway = $getPayments['payment_gateway'];
        $paymentSettings = $getPayments['payment_settings']->where('is_test',$paymentGatway->is_test)->toArray();
        $getKeys = array();
        foreach($paymentSettings as $paymentSetting){
            $getKeys[$paymentSetting['key']] = $paymentSetting['value'];
        }
        $workingKeys = $getKeys['workingKey'];
        $encResponse = $this->request->encResp;
        $rcvdString = $this->_paymentRepository->decrypt ( $encResponse, $workingKeys );
        $order_status = "";
        $decryptValuess = explode ( '&', $rcvdString );
        $dataSize = sizeof ( $decryptValuess );
        for($i = 0; $i < $dataSize; $i ++) {
            $information = explode ( '=', $decryptValuess [$i] );
            if ($i == 3) {
                $order_status = $information [1];
            }
        }
        $user = explode ( '=', $decryptValuess [26] );
        $user_id = $user [1];
        $user = Customer::where ( 'id', $user_id )->first ();
        $subscription = explode ( '=', $decryptValuess [0] );
        $subscription_id = $subscription [1];
        if ($order_status === "Success") {
            $saveTransaction = $this->_transactionRepository->addTransactions($subscription_id, $user,$decryptValuess);
            if($saveTransaction){
                return redirect(url().'/paymentsuccess/'.$saveTransaction->id);
            }
        } else {
            $saveTransaction = $this->_transactionRepository->addTransactions($subscription_id, $user,$decryptValuess);
            return redirect(url().'/paymentfailure/'.$saveTransaction->id);
        }
    }

}