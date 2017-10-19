<?php

/**
 * Contactus Controller
 * To manage the functionalities related to the Contact management
 *
 * @vendor Contus
 *
 * @package cms
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 *
 */
namespace Contus\Cms\Api\Controllers\Cms;

use Contus\Base\ApiController;
use Contus\Cms\Repositories\ContactusRepository;

class ContactusController extends ApiController {
    /**
     * class property to hold the instance of ContactusRepository
     *
     * @var \Contus\Base\Repositories\ContactusRepository Construct method
     */
    public function __construct(ContactusRepository $contactusRepository) {
        parent::__construct ();
        $this->repository = $contactusRepository;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
    }
    /**
     * To get the Contact info.
     *
     * @return json
     */
    public function getInfo() {
        $contacts = $this->repository->getContacts ();
        unset ( $contacts->id );
        return ($contacts) ? $this->getSuccessJsonResponse ( [ 'message' => $contacts ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::subscription.showError' ) );
    }

    /**
     * Submit the contact us form
     *
     * @return json
     */
    public function postContact() {
      $data = $this->repository->addContactus ();
      return ($data) ? $this->getSuccessJsonResponse ( [ 'message'=>trans ( 'cms::latestnews.thanks_for_contacting_us' ) ] ) : $this->getErrorJsonResponse ( [ ], 422);
    }

    /**
     * Submit the Feedback form
     *
     * @return json
     */
    public function postFeedback() {
      $data = $this->repository->addFeedback ();
      return ($data) ? $this->getSuccessJsonResponse ( [ 'message'=>trans ( 'cms::latestnews.thanks_for_contacting_us' ) ] ) : $this->getErrorJsonResponse ( [ ], 422);
    }

    /**
     * To get the Contact us details info.
     *
     * @return json
     */
    public function getContactView($id) {
        $data = $this->repository->getContactInfo ( $id );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'response' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::subscription.showError' ) );
    }
}