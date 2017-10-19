<?php
/**
 * Email Templates Resource Controller
 *
 * To manage the functionalities related to the Email Templates REST api methods
 *
 * @name EmailTemplatesResourceController
 * @vendor Contus
 * @package EmailTemplates
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Cms\Api\Controllers\Cms;

use Illuminate\Http\Request;
use Contus\Base\ApiController;
use Contus\Cms\Repositories\EmailTemplatesRepository;

class EmailResourceController extends ApiController {

    /**
     * Construct method
     */
    public function __construct(EmailTemplatesRepository $emailTemplatesRepository) {
        parent::__construct ();
        $this->repository = $emailTemplatesRepository;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
    }

    /**
     * Funtion to list all the Email Templates using pagenation
     *
     * @return \Contus\Base\response
     */
    public function index() {
        $data = $this->repository->getAllEmailTemplates();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::emailtemplate.showallError' ) );
    }

    /**
     * Function to add new email template
     *
     * @param Request $request
     * @return \Contus\Base\response
     */
    public function store(Request $request) {
        $save = $this->repository->addOrUpdateEmailTemplates ();
        return ($save === 1) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::emailtemplate.add.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::emailtemplate.add.error' ) );
    }

    /**
     * function to get one email template
     *
     * @param Request $request
     * @param int $emailTemplatesid
     * @return \Contus\Base\response
     */
    public function show(Request $request, $emailTemplatesid) {
        $data = $this->repository->getEmailTemplates ( $emailTemplatesid );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [], trans ( 'cms::emailtemplate.showError' ) );
    }

    /**
     * function to update one email template
     *
     * @param Request $request
     * @param int $emailTemplatesid
     * @return \Contus\Base\response
     */
    public function update(Request $request, $emailTemplatesid) {
        $update = $this->repository->addOrUpdateEmailTemplates ( $emailTemplatesid );
        return ($update === 1) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::emailtemplate.update.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::emailtemplate.update.error' ) );
    }

    /**
     * function to delete one email template
     *
     * @param Request $request
     * @param int $emailTemplatesid
     * @return \Contus\Base\response
     */
    public function destroy(Request $request, $emailTemplatesid) {
        $data = $this->repository->deleteEmailTemplate ( $emailTemplatesid );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::emailtemplate.delete.success' )] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::emailtemplate.delete.error' ) );
    }
}
