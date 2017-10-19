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
use Contus\Cms\Repositories\FeedbackRepository;

class FeedbackController extends ApiController {
    /**
     * class property to hold the instance of ContactusRepository
     *
     * @var \Contus\Base\Repositories\ContactusRepository Construct method
     */
    public function __construct(FeedbackRepository $FeedbackRepository) {
        parent::__construct ();
        $this->repository = $FeedbackRepository;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
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

}