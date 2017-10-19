<?php

/**
 * Subscription Scheduler
 *
 * @name SubscriptionScheduler
 * @vendor Contus
 * @package customer
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Customer\Schedulers;

use Contus\Base\Schedulers\Scheduler;
use Contus\Customer\Models\Customer;
use Contus\Notification\Repositories\NotificationRepository;
use Contus\Cms\Repositories\EmailTemplatesRepository;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Contus\Notification\Models\Notification;
use Contus\Cms\Models\EmailTemplates;

class SubscriptionScheduler extends Scheduler {
    /**
     * Scheduler frequency
     *
     * @param \Illuminate\Console\Scheduling\Event $event
     * @return void
     */
    public function __construct() {
        parent::__construct ();
        Log::info ( 'subscription scheduler: ' );
        $this->notification = new NotificationRepository ( new Notification () );
        $this->email = new EmailTemplatesRepository ( new EmailTemplates () );
    }
    /**
     * Function to set the frequency for the scheduler
     *
     * {@inheritDoc}
     * @see \Contus\Base\Schedulers\Scheduler::frequency()
     */
    public function frequency(\Illuminate\Console\Scheduling\Event $event) {
        $event->dailyAt('00:01');
    }
    /**
     * Scheduler call method
     * actual execution go's here
     *
     * @return \Closure
     */
    public function call() {
        return function () {
            $this->filterDay ( 5 );
            $this->filterDay ( 3 );
            $this->filterDay ( 1 );
            $this->filterDay ( 0 );
            $this->filterDay ( - 1 );
        };
    }
    /**
     * Function to segregate schedule task based on day
     *
     * @param int $d
     */
    public function filterDay($d) {
        $customers = new Customer ();
        $date = $customers->freshTimestamp ();
        if ($d < 0) {
            $customers = $customers->whereNotNull ( 'expires_at' )->whereDate ( 'expires_at', '<=', $date->addDays ( $d )->toDateString () )->get ();
        }
        else{
            $customers = $customers->whereNotNull ( 'expires_at' )->whereDate ( 'expires_at', '=', $date->addDays ( $d )->toDateString () )->get ();
        }
        foreach ( $customers as $customer ) {
            $activeSub = $customer->activeSubscriber ()->first ();
            if ($activeSub) {
                $this->notify ( $d, $customer, $activeSub );
            } else {
                $this->notify ( $d, $customer, [ ] );
            }
            if ($d < 0) {
                if ($activeSub) {
                    $subscriptionId = $activeSub->id;
                    $customer->activeSubscriber ()->updateExistingPivot ( $subscriptionId, [ 'is_active' => 0 ], false );
                }
                $customer->expires_at = null;
                $customer->save ();
            }
        }
    }
    /**
     * Function to notify to user and send mail for subscription alert
     *
     * @param int $d
     * @param object $customer
     */
    public function notify($d, $customer, $aSub) {
        $notificationUser = [ 'type' => 'customer','id' => $customer->id ];
        if ($d < 0) {
            $content = 'Your subscription plan Expired please subscribe to use continues unlimited services';
            $email = $this->email->fetchEmailTemplate ( 'subscription_expired_mailto_customer' );
            $email->content = str_replace ( [ '##NAME##','##PLAN##'], [ $customer->name,$aSub->name], $email->content );
        }
        if ($d === 0 && $aSub) {
            $content = 'Your subscription plan Expires today.! please subscribe to use continues unlimited services';
            $email = $this->email->fetchEmailTemplate ( 'subscription_ends_today_mailto_customer' );
            $email->content = str_replace ( [ '##NAME##','##PLAN##' ], [ $customer->name,$aSub->name ], $email->content );
        }
        if ($d > 0 && $aSub) {
            $content = 'Your subscription plan Expires in ' . $d . ' day(s).! please subscribe to use continues unlimited services';
            $email = $this->email->fetchEmailTemplate ( 'subscription_ends_in_days_mailto_customer' );
            $email->content = str_replace ( [ '##NAME##','##PLAN##','##DAYS##' ], [ $customer->name,$aSub->name, $d ], $email->content );
        }
        if ($aSub) {
            $this->notification->addNotifications ( $notificationUser, $content, 'subscription_plans', $aSub->id );
        }
        try{

            $this->notification->email ( $customer, $email->subject, $email->content );
            Log::info ( 'subscription scheduler: try'.$d.'id:'. $customer->id);
        }catch (\Exception $e){
            Log::info ( 'subscription scheduler: catch id :' .$customer->id);
            return true;

        }
    }
}
