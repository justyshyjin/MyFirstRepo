<?php

/**
 * Payment Resource Repository
 *
 * To manage the functionalities related to the Payment REST api methods
 *
 * @name PaymentResourceController
 * @vendor Contus
 * @package Payment
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Payment\Api\Controllers\Payment;

use Illuminate\Http\Request;
use Contus\Base\ApiController;
use Contus\Payment\Repositories\PaymentRepository;

class PaymentResourceController extends ApiController {
    /**
     * Construct method
     */
    public function __construct(PaymentRepository $paymentRepository) {
        parent::__construct ();
        $this->repository = $paymentRepository;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
    }

    /**
     * Funtion to list all the payments using pagenation
     *
     * @return \Contus\Base\response
     */
    public function index() {
        $data = $this->repository->getAllPayments ();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'payment::payment.showallError' ) );
    }

    /**
     * function to get one payment information
     *
     * @param Request $request
     * @param int $paymentId
     * @return \Contus\Base\response
     */
    public function show(Request $request, $paymentId) {
        $data = $this->repository->getPayment ( $paymentId );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [ 'data' => $data ], trans ( 'payment::payment.showError' ) );
    }

    /**
     * function to update one payment information
     *
     * @param Request $request
     * @param int $paymentId
     * @return \Contus\Base\response
     */
    public function update(Request $request, $paymentId) {
        $update = $this->repository->updatePayments ( $paymentId );
        return ($update === 1) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'payment::payment.updated' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'payment::payment.updatedError' ) );
    }
}
