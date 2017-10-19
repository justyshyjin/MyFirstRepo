<?php

/**
 * Transaction Repository
 *
 * To manage the functionalities related to the Transaction module from Transaction Controller
 *
 * @name TransactionRepository
 * @vendor Contus
 * @package Transaction
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Payment\Repositories;

use Contus\User\Models\User;
use Contus\Base\Repository as BaseRepository;
use Illuminate\Http\Request;
use Contus\Customer\Models\Customer;
use Contus\Payment\Models\PaymentTransactions;
use Contus\Base\Helpers\StringLiterals;
use Contus\Customer\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Auth;
use Contus\Customer\Repositories\SubscriptionRepository;

class TransactionRepository extends BaseRepository {
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_transaction;

    /**
     * Class property to hold the key which hold the customer object
     *
     * @var object
     */
    protected $_customer;

    /**
     * Constructor function
     *
     * @param PaymentTransactions $transaction
     * @param CustomerRepository $customer
     * @param SubscriptionRepository $subscription
     */
    public function __construct(PaymentTransactions $transaction, CustomerRepository $customer, SubscriptionRepository $subscription) {
        parent::__construct ();
        $this->_transaction = $transaction;
        $this->_subscription = $subscription;
        $this->_customer = $customer;
    }
    /**
     * Store a newly created payment transaction .
     *
     * @vendor Contus
     *
     * @package Transaction
     * @param $id input
     * @return boolean
     *
     */
    public function addTransactions($package_id = '', $user = '', $decryptValues = '') {
        $transactions = new PaymentTransactions ();
        $dataSize = sizeof ( $decryptValues );
        for($i = 0; $i < $dataSize; $i ++) {
            $transaction = explode ( '=', $decryptValues [$i] );
            if ($i == 3) {
                $transactions->status = $transaction [1];
                $transactions->transaction_message = $transaction [1];
                $transactions->response = $transaction [1];
            }
        }
        $orderId = explode ( '=', $decryptValues [0] ) [1];
        $transactions->payment_method_id = 2;
        $transaction = explode ( '=', $decryptValues [26] );
        $transactions->customer_id = $user->id;
        $transaction = explode ( '=', $decryptValues [17] );
        $transactions->phone = $transaction [1];
        $transaction = explode ( '=', $decryptValues [18] );
        $transactions->email = $transaction [1];
        $transaction = explode ( '=', $decryptValues [11] );
        $transactions->name = $transaction [1];
        $transaction = explode ( '=', $decryptValues [1] );
        $transactions->transaction_id = $transaction [1];
        $transactions->creator_id = $transactions->customer_id;
        $transactions->subscriber_id = $transactions->customer_id;
        $transactions->subscription_plan_id = $orderId;
        $transaction = explode ( '=', $decryptValues [26] );
        $transactions->plan_name = $transaction [1];
        if ($transactions->save ()) {
            if (! (auth ()->user ())) {
                auth ()->loginUsingId ( $transactions->customer_id );
            }
            if ($transactions->status == 'Success') {
                $this->_subscription->addSubscriber ( $orderId );
            }
            return $transactions;
        } else {
            return false;
        }
    }
    /**
     * fetch all the transactions
     *
     * @vendor Contus
     *
     * @package Transaction
     * @return array
     */
    public function getAllTransactions() {
        if (auth ()->user ()->id == 1) {
            return $this->_transaction->paginate ( 10 )->toArray ();
        } else {
            return $this->_transaction->with ( 'getTransactionUser' )->where ( 'customer_id', auth ()->user ()->id )->paginate ( 10 )->toArray ();
        }
    }
    /**
     * fetches one transaction
     *
     * @vendor Contus
     *
     * @package Transaction
     * @param int $transactionId
     * @return object
     */
    public function getTransaction($transactionId) {
        return $this->_transaction->find ( $transactionId );
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
        $this->setGridModel ( $this->_transaction )->setEagerLoadingModels ( [ 'getTransactionUser','getPaymentMethod','getSubscriptionPlan' ] );
        return $this;
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($transactionBuilder) {
        /*
         * updated the all user record only an superadmin user.
         */
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            $transactionBuilder->where ( 'customer_id', $this->authUser->id );
        } else {
            if ($this->authUser->id != 1) {
                $transactionBuilder->where ( 'id', $this->authUser->id )->orWhere ( 'parent_id', $this->authUser->id );
            }
        }
        return $transactionBuilder;
    }

    /**
     * Function to apply filter for search of latestnews grid
     * @vendor Contus
     *
     * @package Payment
     * @param mixed $builderTransaction
     * @return \Illuminate\Database\Eloquent\Builder $builderTransaction The builder object of users grid.
     */
    protected function searchFilter($builderTransaction) {
        $searchRecordUsers = $this->request->has ( StringLiterals::SEARCHRECORD ) && is_array ( $this->request->input ( StringLiterals::SEARCHRECORD ) ) ? $this->request->input ( StringLiterals::SEARCHRECORD ) : [ ];

        /**
         * Loop the search fields of users grid and use them to filter search results.
         */

        foreach ( $searchRecordUsers as $key => $value ) {
            switch ($key) {
                case 'slug' :
                    $builderTransaction = $builderTransaction->whereHas ( 'getTransactionUser', function ($q) use ($value) {
                        $q->where ( 'name', 'like', '%' . $value . '%' );
                    } );
                    break;
                case 'is_active' :
                    if ($key == 'is_active' && $value == 'all') {
                        break;
                    }

                default :
                    $builderTransaction = $builderTransaction->where ( $key, 'like', "%$value%" );
            }
        }
        return $builderTransaction;
    }
    /**
     * Get headings for grid
     * @vendor Contus
     *
     * @package Payment
     * @return array
     */
    public function getGridHeadings() {
        return [ StringLiterals::GRIDHEADING => [ [ 'name' => trans ( 'payment::transaction.transaction_id' ),StringLiterals::VALUE => '','sort' => true ],[ 'name' => trans ( 'payment::transaction.customer_name' ),StringLiterals::VALUE => '','sort' => true ],

        [ 'name' => trans ( 'payment::transaction.status' ),StringLiterals::VALUE => '','sort' => true ],[ 'name' => trans ( 'payment::transaction.created_at' ),StringLiterals::VALUE => '','sort' => true ],[ 'name' => trans ( 'payment::transaction.action' ),StringLiterals::VALUE => '','sort' => true ] ] ];
    }

    /**
     * Function to fetch all the details of a transaction from the database.
     *
     * @param integer $id
     * The id of the transaction whose data are to be fetched.
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|NULL The information of the video.
     */
    public function getCompleteTransaction($id) {
        return $this->_transaction->with ( [ 'getTransactionUser','getPaymentMethod' ] )->where ( 'id', $id )->first ();
    }
}