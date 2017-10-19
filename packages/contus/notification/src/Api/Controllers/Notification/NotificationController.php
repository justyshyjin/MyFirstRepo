<?php

/**
 * Notification Repository
 *
 * To manage the functionalities related to the Notification api methods
 *
 * @name NotificationController
 * @vendor Contus
 * @package Notification
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Notification\Api\Controllers\Notification;

use Contus\Base\ApiController;
use Contus\Notification\Repositories\NotificationRepository;

class NotificationController extends ApiController {
    /**
     * Construct method
     */
    public function __construct(NotificationRepository $notificationRepository) {
        parent::__construct ();
        $this->repository = $notificationRepository;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
    }

    /**
     * Funtion to list all the notifications using pagenation
     *
     * @return \Contus\Base\response
     */
    public function getList() {
        $data = $this->repository->getAllNotifications ();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'notification::notification.showallError' ) );
    }

    /**
     * function to get one notification information
     *
     * @param int $notificationId
     * @return \Contus\Base\response
     */
    public function getShow($notificationId) {
        $data = $this->repository->getNotification ( $notificationId );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [ 'data' => $data ], trans ( 'notification::notification.showError' ) );
    }
    /**
     * function to get the id and update the notification is read
     *
     * @param int $notificationId
     * @return \Contus\Base\response
     */
    public function getRead($notificationId) {
        $data = $this->repository->updateRead ( $notificationId );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [ 'data' => $data ], trans ( 'notification::notification.showError' ) );
    }
    /**
     * function to get the notification
     *
     * @return \Contus\Base\response
     */
    public function getNotifications() {
        $data = $this->repository->getUserNotifications ();
        $data ['notification_settings'] = $this->repository->getNotificationSettings ();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'response' => $data,'message' => trans ( 'notification::notification.success' ) ] ) : $this->getErrorJsonResponse ( [ 'data' => $data ], trans ( 'notification::notification.showError' ) );
    }

    /**
     * Function used to post the notification alert based on user choosing from front end
     *
     * @return \Contus\Base\response
     */
    public function postNotificationSettings() {
        $data = $this->repository->updateSettings ();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'response' => $data,'message' => trans ( 'notification::notification.success' ) ] ) : $this->getErrorJsonResponse ( [ 'data' => $data ], trans ( 'notification::notification.showError' ) );
    }
    /**
     * Change the notification from unready to read
     *
     * @return \Contus\Base\response
     */
    public function isReadNotifications() {
        $data = $this->repository->isreadSettings ();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'response' => $data,'message' => trans ( 'notification::notification.isreadsuccess' ) ] ) : $this->getErrorJsonResponse ( [ 'data' => $data ], trans ( 'notification::notification.showError' ) );
    }
    /**
     * Function to trigger notification async through curl
     */
    public function setNotification() {
        if (config ()->get ( 'auth.providers.users.table' ) !== 'customers' && ! (auth ()->user ())) {
            $id = $this->request->header ( 'x-user-id' );
            auth ()->loginUsingId ( $id );
        }
        $this->repository->setNotify ();
    }
}
