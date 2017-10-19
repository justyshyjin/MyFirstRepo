<?php
/**
 * Sms Templates Resource Controller
 *
 * To manage the functionalities related to the Sms Templates REST api methods
 *
 * @name SmsTemplatesResourceController
 * @vendor Contus
 * @package SmsTemplates
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Cms\Api\Controllers\Cms;

use Illuminate\Http\Request;
use Contus\Base\ApiController;
use Contus\Cms\Repositories\SmsTemplatesRepository;

class SmsResourceController extends ApiController {

    /**
     * Construct method
     */
    public function __construct(SmsTemplatesRepository $smsTemplatesRepository) {
        parent::__construct ();
        $this->repository = $smsTemplatesRepository;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
    }

    /**
     * Funtion to list all the Sms Templates using pagenation
     *
     * @return \Contus\Base\response
     */
    public function index() {
        $data = $this->repository->getAllSmsTemplates();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::smstemplate.showallError' ) );
    }

    /**
     * Function to add new sms template
     *
     * @param Request $request
     * @return \Contus\Base\response
     */
    public function store(Request $request) {
        $save = $this->repository->addOrUpdateSmsTemplates ();
        return ($save === 1) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::smstemplate.add.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::smstemplate.add.error' ) );
    }

    /**
     * function to get one sms template
     *
     * @param Request $request
     * @param int $smsTemplatesid
     * @return \Contus\Base\response
     */
    public function show(Request $request, $smsTemplatesid) {
        $data = $this->repository->getSmsTemplates ( $smsTemplatesid );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [], trans ( 'cms::smstemplate.showError' ) );
    }

    /**
     * function to update one sms template
     *
     * @param Request $request
     * @param int $smsTemplatesid
     * @return \Contus\Base\response
     */
    public function update(Request $request, $smsTemplatesid) {
        $update = $this->repository->addOrUpdateSmsTemplates ( $smsTemplatesid );
        return ($update === 1) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::smstemplate.update.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::smstemplate.update.error' ) );
    }

    /**
     * function to delete one sms template
     *
     * @param Request $request
     * @param int $smsTemplatesid
     * @return \Contus\Base\response
     */
    public function destroy(Request $request, $smsTemplatesid) {
        $data = $this->repository->deleteSmsTemplate ( $smsTemplatesid );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::smstemplate.delete.success' )] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::smstemplate.delete.error' ) );
    }
}
