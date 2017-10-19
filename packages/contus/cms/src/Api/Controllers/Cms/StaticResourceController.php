<?php
/**
 * Static Content Resource Controller
 *
 * To manage the functionalities related to the Sms Templates REST api methods
 *
 * @name StaticResourceController
 * @vendor Contus
 * @package cms
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Cms\Api\Controllers\Cms;

use Illuminate\Http\Request;
use Contus\Base\ApiController;
use Contus\Cms\Repositories\StaticContentRepository;

class StaticResourceController extends ApiController {

    /**
     * Construct method
     */
    public function __construct(StaticContentRepository $TemplatesRepository) {
        parent::__construct ();
        $this->repository = $TemplatesRepository;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
    }

    /**
     * Funtion to list all the Static Contents using pagenation
     *
     * @return \Contus\Base\response
     */
    public function index() {
        $data = $this->repository->getAllStaticContents();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::staticcontent.showallError' ) );
    }

    /**
     * Function to add new static content
     *
     * @param Request $request
     * @return \Contus\Base\response
     */
    public function store(Request $request) {
        $save = $this->repository->addOrUpdateStaticContent ();
        return ($save === 1) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::staticcontent.add.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::staticcontent.add.error' ) );
    }

    /**
     * function to get one static content
     *
     * @param Request $request
     * @param int $contentId
     * @return \Contus\Base\response
     */
    public function show(Request $request, $contentId) {
        $data = $this->repository->getStaticContent ( $contentId );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [], trans ( 'cms::staticcontent.showError' ) );
    }

    /**
     * function to update one Static content
     *
     * @param Request $request
     * @param int $contentId
     * @return \Contus\Base\response
     */
    public function update(Request $request, $contentId) {
        $update = $this->repository->addOrUpdateStaticContent ( $contentId );
        return ($update === 1) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::staticcontent.update.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::staticcontent.update.error' ) );
    }

    /**
     * function to delete one sms template
     *
     * @param Request $request
     * @param int $contentId
     * @return \Contus\Base\response
     */
    public function destroy(Request $request, $contentId) {
        $data = $this->repository->deleteStaticContent ( $contentId );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::staticcontent.delete.success' )] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::staticcontent.delete.error' ) );
    }
}
