<?php

/**
 * Sms Controller
 * To manage the functionalities related to the Sms template gird api methods
 *
 * @name Sms Controller
 * @vendor Contus
 * @package cms
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Api\Controllers\Cms;

use Contus\Base\ApiController;
use Contus\Cms\Repositories\SmsTemplatesRepository;
use Contus\Base\Helpers\StringLiterals;

class SmsController extends ApiController {
    /**
     * class property to hold the instance of SmsTemplatesRepository
     *
     * @var \Contus\Base\Repositories\SmsTemplatesRepository
     */
    public $smsRepository;
    /**
     * Construct method
     */
    public function __construct(SmsTemplatesRepository $smsRepository) {
        parent::__construct ();
        $this->repository = $smsRepository;
    }

    /**
     * To get the Sms template info.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo() {
        return $this->getSuccessJsonResponse ( [ 'info' => [ 'rules' => $this->repository->getRules (),'allSms' => $this->repository->getAllSmsTemplates () ] ] );
    }

    /**
     * Store a newly created Sms template.
     *
     * @return \Illuminate\Http\Response
     */
    public function postAdd() {
        $isCreated = false;

        if ($this->repository->addOrUpdateSmsTemplates ()) {
            $isCreated = true;
            $this->request->session ()->flash ( StringLiterals::SUCCESS, trans ( 'cms::smstemplate.add.success' ) );
        }

        return ($isCreated) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::smstemplate.add.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::smstemplate.add.error' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function postEdit($smsId) {
        $isCreated = false;

        if ($this->repository->addOrUpdateSmsTemplates ( $smsId )) {
            $isCreated = true;
            $this->request->session ()->flash ( 'success', trans ( 'cms::smstemplate.update.success' ) );
        }

        return ($isCreated) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::smstemplate.update.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::smstemplate.update.error' ) );
    }
}
