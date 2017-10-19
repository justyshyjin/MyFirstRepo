<?php

/**
 * ContactUs Repository
 *
 * To manage the functionalities related to the ContactUs Controller
 *
 * @vendor Contus
 *
 * @package Cms
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Base\Helpers\StringLiterals;
use Contus\Cms\Models\StaticPages;
use Contus\Cms\Models\Feedback;

class FeedbackRepository extends BaseRepository {

    /**
     * Class property to hold the key which hold the Contact us object
     *
     * @var object
     */
    protected $_feedback;
    /**
     * Construct method
     *
     * @param Contus\Cms\Models\Contactus $contactUs
     */
    public function __construct(Feedback $feedback) {
        parent::__construct ();
        $this->_feedback = $feedback;
        $this->setRules ( [ 'name' => 'required|max:100|min:3','email' => 'required|email','phone' => 'required|numeric|min:6','message' => 'required|max:255' ] );
    }
    /**
     * Store a newly created static content or update the static content.
     *
     * @param $id input
     * @return boolean
     */
    public function addFeedback($id = null) {
        $this->_validate ();
        $feedback = new Feedback ();
        $feedback->is_active = 1;
        $feedback->creator_id = 1;
        $feedback->fill ( $this->request->all () );
        return ($feedback->save ()) ? 1 : 0;
    }
   
}