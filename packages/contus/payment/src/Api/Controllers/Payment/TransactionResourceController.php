<?php

/**
 * Transaction Resource Repository
 *
 * To manage the functionalities related to the Transaction REST api methods
 *
 * @name TransactionResourceController
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
use Contus\Payment\Repositories\TransactionRepository;

class TransactionResourceController extends ApiController {
    /**
     * Construct method
     */
    public function __construct(TransactionRepository $transactionRepository) {
        parent::__construct ();
        $this->repository = $transactionRepository;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
    }
    
    /**
     * Funtion to list all the transactions using pagenation
     *
     * @return \Contus\Base\response
     */
    public function index() {
        $data = $this->repository->getAllTransactions ();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'payment::transaction.showallError' ) );
    }
    
    /**
     * function to get one transaction information
     *
     * @param Request $request 
     * @param int $transactionId 
     * @return \Contus\Base\response
     */
    public function show(Request $request, $transactionId) {
        $data = $this->repository->getTransaction ( $transactionId );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [ 'data' => $data ], trans ( 'payment::transaction.showError' ) );
    }
}
