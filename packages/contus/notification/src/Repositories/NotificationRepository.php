<?php

/**
 * Notification Repository
 *
 * To manage the functionalities related to the Notification module from Notification Controller
 *
 * @name NotificationRepository
 * @vendor Contus
 * @package Notification
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Notification\Repositories;

use Contus\User\Models\User;
use Contus\Base\Repository as BaseRepository;
use Contus\Notification\Models\Notification;
use Contus\Customer\Models\Customer;
use Contus\Notification\Traits\NotificationTrait as Notifiy;

class NotificationRepository extends BaseRepository {
    use Notifiy;
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_notification;
    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package Notification
     * @param Contus\Notification\Models\Notification $notification
     */
    public function __construct() {
        parent::__construct ();
        $this->_notification = new Notification ();
        $this->_customer = new Customer ();
    }
    /**
     * Store a newly created notification or update the notification.
     *
     * @vendor Contus
     *
     * @package Notification
     * @param array $user
     * @param string $content
     * @param string $type
     * @param int $type_id
     * @return boolean
     */
    public function addNotifications(array $user, $content, $type, $type_id) {
        $userType = $user ['type'];
        $userId = $user ['id'];
        $notification = new Notification ();
        $notification->user_id = ($userType == 'admin') ? $userId : 0;
        $notification->customer_id = ($userType == 'customer') ? $userId : 0;
        $notification->content = $content;
        $notification->type = $type;
        $notification->type_type = $type;
        $notification->type_id = $type_id;
        $notification->creator_id = 1;
        $notification->creator_type = 'users';
        return ($notification->save ()) ? 1 : 0;
    }
    /**
     * This function used to display the notification count in Dashboard
     *
     * @return object
     */
    public function isreadSettings() {
        if (config ()->get ( 'auth.providers.users.table' ) == 'customers') {
            $customer_id = $this->authUser->id;
            return $this->_notification->where ( 'customer_id', $customer_id )->where ( 'is_read', 0 )->update ( [ 'is_read' => 1 ] );
        }
    }
    /**
     * Updates the notification is read from the front end
     *
     * @vendor Contus
     *
     * @package Notification
     * @param int $id
     */
    public function updateRead($id) {
        $this->_notification = $this->_notification->find ( $id );
        if (! is_object ( $this->_notification )) {
            return 0;
        }
        $this->_notification->is_read = 1;
        $this->_notification->updator_id = $this->authUser->id;
        return ($this->_notification->save ()) ? 1 : 0;
    }
    /**
     * fetch all the notifications
     *
     * @vendor Contus
     *
     * @package Notification
     * @return array
     */
    public function getAllNotifications() {
        return $this->_notification->paginate ( 10 )->toArray ();
    }
    /**
     * fetches one notification
     *
     * @vendor Contus
     *
     * @package Notification
     * @param int $notificationId
     * @return object
     */
    public function getNotification($notificationId) {
        $this->_notification = $this->_notification->find ( $notificationId );
        if (! is_object ( $this->_notification )) {
            return 0;
        }
        if ($this->_notification->user_id) {
            $this->_notification->users = $this->_notification->users ()->get ();
        }
        if ($this->_notification->customer_id) {
            $this->_notification->customers = $this->_notification->customers;
        }
        return $this->_notification;
    }
    /**
     * function to get the notification for user or customer
     * @vendor Contus
     *
     * @package Notification
     * @param int $notificationId
     * @return object
     */
    public function getUserNotifications() {
        if (config ()->get ( 'auth.providers.users.table' ) == 'customers') {
            $user_id = $this->authUser->id;
            if (($this->request->header ( 'x-request-type' ) == 'mobile')) {
                return $this->_notification->where ( 'customer_id', $user_id )->with ( [ 'video_notification' ] )->orderBy ( 'id', 'desc' )->paginate ( 10 )->toArray ();
            } else {
                return $this->_notification->with ( [ 'customers','users','videos' ] )->where ( 'customer_id', $user_id )->orderBy ( 'id', 'desc' )->paginate ( 10 )->toArray ();
            }
        } else {
            $customer_id = $this->authUser->id;
            return $this->_notification->with ( [ 'customers','users','videos' ] )->where ( 'user_id', $customer_id )->orderBy ( 'id', 'desc' )->paginate ( 10 )->toArray ();
        }
    }
    /**
     * This function used to display the notification count in Dashboard
     *
     * @return object
     */
    public function getNotificationCount() {
        return $this->_notification->where ( 'customer_id', $this->authUser->id )->where ( 'is_read', 0 )->get ()->count ();
    }
    /**
     * User can able to update the notification settings regarding notification on/off
     */
    public function updateSettings() {
        if (! empty ( auth ()->user ()->id )) {
            $test = $this->request->all ();
            foreach ( $test as $value ) {
                if (isset ( $value ['notify_comment'] )) {
                    $this->authUser->notify_comment = $value ['notify_comment'];
                    $this->authUser->save ();
                }
                if (isset ( $value ['notify_reply_comment'] )) {
                    $this->authUser->notify_reply_comment = $value ['notify_reply_comment'];
                    $this->authUser->save ();
                }
                if (isset ( $value ['notify_videos'] )) {
                    $this->authUser->notify_videos = $value ['notify_videos'];
                    $this->authUser->save ();
                }
                if (isset ( $value ['notify_newsletter'] )) {
                    $this->authUser->notify_newsletter = $value ['notify_newsletter'];
                    $this->authUser->save ();
                }
            }
        }
        return true;
    }
    public function getNotificationSettings() {
        if (! empty ( auth ()->user ()->id )) {
            $hiddenArray= array('notification_status','notify_newsletter','notify_reply_comment','notify_videos','notify_comment');
            return $this->_customer->where('id', auth ()->user ()->id )->first()->makeVisible($hiddenArray);
        }
        return true;
    }
}