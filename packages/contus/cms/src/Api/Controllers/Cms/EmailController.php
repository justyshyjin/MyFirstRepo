<?php

/**
 * Email Controller
 * To manage the functionalities related to the Latest News gird api methods
 * 
 * @name Email Controller
 * @vendor Contus
 * @package cms
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Api\Controllers\Cms;

use Contus\Base\ApiController;
use Contus\Cms\Repositories\EmailTemplatesRepository;
use Contus\Base\Helpers\StringLiterals;

class EmailController extends ApiController {
    /**
     * class property to hold the instance of EmailTemplatesRepository
     *
     * @var \Contus\Base\Repositories\EmailTemplatesRepository
     */
    public $emailRepository;
    /**
     * Construct method
     */
    public function __construct(EmailTemplatesRepository $emailRepository) {
        parent::__construct ();
        $this->repository = $emailRepository;
    }
    
    /**
     * To get the email template info.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo() {
        return $this->getSuccessJsonResponse ( [ 'info' => [ 'rules' => $this->repository->getRules (),'allEmails' => $this->repository->getAllEmailTemplates () ] ] );
    }
    
    /**
     * Store a newly created email template.
     *
     * @return \Illuminate\Http\Response
     */
    public function postAdd() {
        $isCreated = false;
        
        if ($this->repository->addOrUpdateEmailTemplates ()) {
            $isCreated = true;
            $this->request->session ()->flash ( StringLiterals::SUCCESS, trans ( 'cms::emailtemplate.add.success' ) );
        }
        
        return ($isCreated) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::emailtemplate.add.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::emailtemplate.add.error' ) );
    }
    
    /**
     * To get the Email content info.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEmailData($id) {
        $data = $this->repository->getEmailTemplates ( $id );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'response' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::subscription.showError' ) );
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param int $id 
     * @return \Illuminate\Http\Response
     */
    public function postEdit($emailId) {
        $isCreated = false;
        
        if ($this->repository->addOrUpdateEmailTemplates ( $emailId )) {
            $isCreated = true;
            $this->request->session ()->flash ( 'success', trans ( 'cms::emailtemplate.update.success' ) );
        }
        
        return ($isCreated) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::emailtemplate.update.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::emailtemplate.update.error' ) );
    }
}
