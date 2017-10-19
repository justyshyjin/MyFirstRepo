<?php

/**
 * Notification Controller
 *
 * To manage the Notification page view funtionalities
 *
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Notification\Http\Controllers\Notification;

use Contus\Base\Controller as BaseController;
use Contus\Notification\Repositories\NotificationRepository;

class NotificationController extends BaseController {
    /**
     * Construct method
     */
    public function __construct(NotificationRepository $NotificationRepository) {
        parent::__construct ();
        $this->_repository = $NotificationRepository;
        $this->_repository->setRequestType ( static::REQUEST_TYPE );
    }
    /**
     * Method to return index blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index() {
        return view ( 'notification::user.account.notifications' );
    }
}
