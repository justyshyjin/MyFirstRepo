<?php

/**
 * Subscription Repository
 *
 * To manage the functionalities related to the Subscription module from Subscription Resource Controller
 *
 * @name SubscriptionRepository
 * @vendor Contus
 * @package Customer
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Customer\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Customer\Models\SubscriptionPlan;
use Contus\Notification\Repositories\NotificationRepository;
use Contus\Cms\Repositories\EmailTemplatesRepository;
use Illuminate\Support\Facades\Mail;
use Contus\Base\Helpers\StringLiterals;
use App\Subscriber;
use Contus\Customer\Models\Subscribers;
use Contus\Payment\Repositories\PaymentRepository;

class SubscriptionRepository extends BaseRepository {

    /**
     * Class property to hold the key which hold the subscription object
     *
     * @var object
     */
    protected $_subscription;
    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package Customer
     * @param Contus\Customer\Models\Subscription $subscription
     */
    public function __construct(SubscriptionPlan $subscription, PaymentRepository $paymentrepositay, Subscribers $subscriber, NotificationRepository $notification, EmailTemplatesRepository $email) {
        parent::__construct ();
        $this->_subscription = $subscription;
        $this->_subscriber = $subscriber;
        $this->notification = $notification;
        $this->email = $email;
        $this->payment = $paymentrepositay;
    }
    /**
     * Store a newly created subscription or update the subscription.
     *
     * @vendor Contus
     *
     * @package Customer
     * @param $id input
     * @return boolean
     */
    public function addOrUpdateSubscriptions($id = null) {
        if (! empty ( $id )) {
            $subscription = $this->_subscription->find ( $id );
            if (! is_object ( $subscription )) {
                return false;
            }
            $this->setRules ( [ 'name' => 'sometimes|required|max:255','is_active' => 'sometimes|required|boolean','type' => 'sometimes|required|max:255','amount' => 'sometimes|required|between:0,99999999.99','description' => 'sometimes|required','duration' => 'sometimes|required|integer|min:1' ] );
            $subscription->updator_id = $this->authUser->id;
        } else {
            $this->setRules ( [ 'name' => 'required|max:255','type' => 'required|max:255','amount' => 'required|between:0,99999999.99','description' => 'required','duration' => 'required|integer|min:1' ] );
            $subscription = new SubscriptionPlan ();
            $subscription->is_active = 1;
            $subscription->creator_id = $this->authUser->id;
        }
        $this->_validate ();
        $subscription->fill ( $this->request->except ( '_token' ) );
        return ($subscription->save ()) ? 1 : 0;
    }
    /**
     * fetch all the subscriptions
     *
     * @vendor Contus
     *
     * @package Customer
     * @return array
     */
    public function getAllSubscriptions() {
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            return $this->_subscription->where ( 'is_active', 1 )->orderBy('id','asc')->paginate ( 10 )->toArray ();
        } else {
            return $this->_subscription->paginate ( 10 )->toArray ();
        }
    }
    /**
     * fetches one subscription
     *
     * @vendor Contus
     *
     * @package Customer
     * @param int $subscriptionId
     * @return object
     */
    public function getSubscription($subscriptionId) {
        return $this->_subscription->find ( $subscriptionId );
    }
    /**
     * fetches one subscription using slug
     *
     * @vendor Contus
     *
     * @package Customer
     * @param int $subscriptionId
     * @return object
     */
    public function proccessSubscriptionPayment($subscriptionSlug) {
            $getSubscriptionplan = $this->_subscription->where ( $this->getKeySlugorId(), $subscriptionSlug )->first ();
            $transactionamount = $getSubscriptionplan->amount;
            $orderId = $getSubscriptionplan->id;
            if(isset($getSubscriptionplan)&&count($getSubscriptionplan)>0){
                $getPaymentdetails =  $this->payment->postccavRequestHandler($transactionamount,$orderId);
            }
        return $getPaymentdetails;
    }
    public function getCCAvenueUrl(){
        $getPayments = $this->payment->getPayment(2);
        $getPaymentGatway = $getPayments['payment_gateway'];
        $getPaymentSettings = $getPayments['payment_settings']->where('is_test',$getPaymentGatway->is_test)->toArray();
        $urlData = array();
        foreach($getPaymentSettings as $getPaymentSetting){
            $urlData[$getPaymentSetting['key']] = $getPaymentSetting['value'];
        }
        return $urlData;
    }
    /**
     * delete subscription.
     *
     * @vendor Contus
     *
     * @package Customer
     * @param $subscriptionId input
     * @return boolean
     */
    public function deleteSubscription($subscriptionId) {
        $data = $this->_subscription->find ( $subscriptionId );
        if ($data) {
            $data->delete ();
            return true;
        } else {
            return false;
        }
    }
    /**
     * Function to get subscriber information
     */
    public function getSubscriberInfo() {
        return Subscriber::where ( 'customer_id', auth ()->user ()->id )->get ();
    }
    /**
     * Function to upgrade subscription plan
     * Notify about upgrade of plan to admin and user
     *
     * @vendor Contus
     *
     * @package Customer
     * @param string $slug
     * @return object
     */
    public function addSubscriber($slug) {
        $allIds = $this->_subscription->lists ( 'id' );
        $this->_subscription = $this->getSubscription ( $slug );
        $date = $this->_subscription->freshTimestamp ();
        $dateEnd = $this->_subscription->freshTimestamp ()->addDays ( $this->_subscription->duration );
        foreach ( $allIds as $id ) {
            auth()->user()->Subscriber ()->updateExistingPivot ( $id, [ 'is_active' => 0 ], false );
        }
        auth()->user()->Subscriber ()->attach ( [ $this->_subscription->id => [ 'is_active' => 1,'start_date' => $date,'end_date' => $dateEnd,'created_at' => $date ] ] );
        auth()->user()->expires_at = $dateEnd;
        auth()->user()->save ();
        $notificationUser = [ 'type' => 'admin','id' => 1 ];
        $content = auth()->user()->name . ' has upgraded subscription to ' . auth()->user()->activeSubscriber ()->first ()->name;
        $this->notification->addNotifications ( $notificationUser, $content, 'subscription_plans', auth()->user()->id );
        $email = $this->email->fetchEmailTemplate ( 'upgrade_mailto_admin' );
        $email->content = str_replace ( [ '##NAME##','##PLAN##' ], [ auth()->user()->name,auth()->user()->activeSubscriber ()->first ()->name ], $email->content );
        $this->notification->email ( auth()->user(), $email->subject, $email->content );
        $notificationUser = [ 'type' => 'customer','id' => auth()->user()->id ];
        $content = 'Your subscription plan upgraded to ' . auth()->user()->activeSubscriber ()->first ()->name;
        $this->notification->addNotifications ( $notificationUser, $content, 'subscription_plans', auth()->user()->id );
        $email = $this->email->fetchEmailTemplate ( 'upgrade_mailto_customer' );
        $email->content = str_replace ( [ '##USERNAME##','##PLAN##' ], [ auth()->user()->name,auth()->user()->activeSubscriber ()->first ()->name ], $email->content );
        $this->notification->email ( auth()->user(), $email->subject, $email->content );
        return auth()->user()->activeSubscriber ()->first ();
    }
    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     * @vendor Contus
     *
     * @package Customer
     * @return Contus\User\Repositories\BaseRepository
     */
    public function prepareGrid() {
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            $this->setGridModel ( $this->_subscriber )->setEagerLoadingModels ( [ 'subscriptionplan' ] );
        } else {
            $this->setGridModel ( $this->_subscription );
        }
        return $this;
    }

    /**
     * update grid records collection query
     *
     * @param mixed $subscriptionBuilder
     * @return mixed
     */
    protected function updateGridQuery($subscriptionBuilder) {
        /*
         * updated the all user record only an superadmin user.
         */
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            $subscriptionBuilder = $subscriptionBuilder->where ( 'customer_id', $this->authUser->id );
        } else {
            if ($this->authUser->id != 1) {
                $subscriptionBuilder->where ( 'id', $this->authUser->id )->orWhere ( 'parent_id', $this->authUser->id );
            }
        }

        return $subscriptionBuilder;
    }

    /**
     * Function to apply filter for search of latestnews grid
     * @vendor Contus
     *
     * @package Customer
     * @param mixed $builderSubscription
     * @return \Illuminate\Database\Eloquent\Builder $builderUsers The builder object of users grid.
     */
    protected function searchFilter($builderSubscription) {
        $searchRecordUsers = $this->request->has ( StringLiterals::SEARCHRECORD ) && is_array ( $this->request->input ( StringLiterals::SEARCHRECORD ) ) ? $this->request->input ( StringLiterals::SEARCHRECORD ) : [ ];
        /**
         * Loop the search fields of subscriptions grid and use them to filter search results.
         */

        foreach ( $searchRecordUsers as $key => $value ) {
            if ($key == 'is_active' && $value == 'all') {
                continue;
            } else {
                $builderSubscription = $builderSubscription->where ( $key, 'like', "%$value%" );
            }
        }

        return $builderSubscription;
    }
    /**
     * Get headings for grid
     * @vendor Contus
     * .
     *
     * @package Customer
     * @return array
     */
    public function getGridHeadings() {
        if (config ()->get ( 'auth.providers.users.table' ) === 'customers') {
            return [ StringLiterals::GRIDHEADING => [ [ 'name' => trans ( 'customer::subscription.subscription_name' ),StringLiterals::VALUE => '','sort' => true ],[ 'name' => trans ( 'customer::subscription.start_date' ),StringLiterals::VALUE => '','sort' => false ],

            [ 'name' => trans ( 'customer::subscription.end_date' ),StringLiterals::VALUE => '','sort' => true ],[ 'name' => trans ( 'customer::subscription.status' ),StringLiterals::VALUE => 'is_active','sort' => false ],[ 'name' => trans ( 'customer::subscription.created_date' ),StringLiterals::VALUE => '','sort' => true ],[ 'name' => trans ( 'customer::subscription.action' ),StringLiterals::VALUE => '','sort' => false ] ] ];
        } else {
            return [ StringLiterals::GRIDHEADING => [ [ 'name' => trans ( 'customer::subscription.subscription_name' ),StringLiterals::VALUE => '','sort' => true ],

            [ 'name' => trans ( 'customer::subscription.type' ),StringLiterals::VALUE => '','sort' => false ],[ 'name' => trans ( 'customer::subscription.description' ),StringLiterals::VALUE => '','sort' => true ],[ 'name' => trans ( 'customer::subscription.amount' ),StringLiterals::VALUE => '','sort' => true ],[ 'name' => trans ( 'customer::subscription.duration' ),StringLiterals::VALUE => '','sort' => true ],

            [ 'name' => trans ( 'customer::subscription.created_at' ),StringLiterals::VALUE => '','sort' => false ],[ 'name' => trans ( 'customer::subscription.status' ),StringLiterals::VALUE => 'is_active','sort' => false ],[ 'name' => trans ( 'customer::subscription.action' ),StringLiterals::VALUE => '','sort' => false ] ] ];
        }
    }
    public function sendPaymentlink(){
      $this->request->input ( 'user_id' );
      $paymentPlan = $this->request->input('payment_plan');
      $this->getSubscription($paymentPlan)->slug;
      $link = url('/').'/subscribeinfo';
      $email = $this->email->fetchEmailTemplate ( 'paymentlink_mailto_customer' );
      $email->content = str_replace ( [ '##USERNAME##','##URL##' ], [ auth()->user()->name,$link ], $email->content );
      $this->notification->email ( auth()->user(), $email->subject, $email->content);
      return true;
    }
}
