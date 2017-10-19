<?php

/**
 * subscription Plan Controller
 *
 * To manage the functionalities related to the subscription plan api methods
 *
 * @name SubscriptionPlanController
 * @vendor Contus
 * @package customer
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Customer\Api\Controllers\Customer;

use Contus\Base\ApiController;
use Contus\Customer\Repositories\SubscriptionRepository;
use Contus\Base\Helpers\StringLiterals;
use Carbon\Carbon;

class SubscriptionPlanController extends ApiController {

    /**
     * Construct method
     */
    public function __construct(SubscriptionRepository $subscriptionRepository) {
        parent::__construct ();
        $this->repository = $subscriptionRepository;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
    }

    /**
     * Funtion to list all the subscriptions using pagenation
     *
     * @return \Contus\Base\response
     */
    public function fetchAll() {
        $data = $this->repository->getAllsubscriptions ();
        foreach ( $data ['data'] as $ak => $av ) {
            unset ( $data ['data'] [$ak] ['id'] );
        }
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::subscription.showallError' ) );
    }

    /**
     * Method to fethc all data of subscriber
     *
     * @return \Contus\Base\response
     */
    public function fetchApiInfoAll() {
        $data = $this->repository->getSubscriberInfo ();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::subscription.showallError' ) );
    }

    /**
     * Funtion to list all the subscriptions using pagination
     *
     * @return \Contus\Base\response
     */
    public function fetchOne($slug) {
        $data = $this->repository->getSubscriptionSlug ( $slug );
        unset ( $data->id );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::subscription.showError' ) );
    }

    /**
     * To get the Subscription info.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo() {
        return $this->getSuccessJsonResponse ( [ 'info' => [ 'rules' => $this->repository->getRules (),'allSubscriptions' => $this->repository->getAllsubscriptions () ] ] );
    }

    /**
     * To get the Subscription index.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex() {
        $data = $this->repository->getAllsubscriptions ();
        if (auth ()->user ()) {
            $data ['subscribed_plan'] = auth ()->user ()->activeSubscriber ()->first ();
            $data ['plan_duration_left'] = '';
            if ($data ['subscribed_plan']) {
                $end = Carbon::parse ( $data ['subscribed_plan']->pivot->end_date );
                $now = Carbon::now ();
                $length = $end->diffInDays ( $now );
                $data ['plan_duration_left'] = $length . ' days left';
            }
        } else {
            $data ['subscribed_plan'] = null;
            $data ['plan_duration_left'] = '';
        }
        return ($data) ? $this->getSuccessJsonResponse ( [ 'response' => [ 'allSubscriptions' => $data,'allSubscriptions' => $data ] ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::subscription.showError' ) );
    }

    /**
     * Store a newly created Subscription.
     *
     * @return \Illuminate\Http\Response
     */
    public function postAdd() {
        $isCreated = false;

        if ($this->repository->addOrUpdateSubscriptions ()) {
            $isCreated = true;
            $this->request->session ()->flash ( StringLiterals::SUCCESS, trans ( 'cms::subscription.add.success' ) );
        }

        return ($isCreated) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'customer::subscription.add.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::subscription.add.error' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function postEdit($subscriptionId) {
        $isCreated = false;

        if ($this->repository->addOrUpdateSubscriptions ( $subscriptionId )) {
            $isCreated = true;
            $this->request->session ()->flash ( 'success', trans ( 'customer::subscription.update.success' ) );
        }

        return ($isCreated) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'customer::subscription.update.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::subscription.update.error' ) );
    }
    /**
     * Method to Update Subscription
     *
     * @param string $slug
     * @return \Contus\Base\response
     */
    public function updatesubscription($slug) {
        $paymentData = $this->repository->proccessSubscriptionPayment ( $slug );

        if (isset ( $paymentData )) {
            return view ( 'payment::customer.payment.ccavRequestHandler' )->with ( 'encrypted_data', $paymentData );
        } else {
            return false;
        }
    }
}
