<?php

/**
 * Latest News Resource Controller
 *
 * To manage the functionalities related to the Latest News REST api methods
 * 
 * @name LatestNewsController
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
use Contus\Cms\Repositories\LatestNewsRepository;

class LatestNewsResourceController extends ApiController {
    
    /**
     * Construct method
     */
    public function __construct(LatestNewsRepository $TemplatesRepository) {
        parent::__construct ();
        $this->repository = $TemplatesRepository;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
    }
    
    /**
     * Funtion to list all the Latest News using pagenation
     * 
     * @return \Contus\Base\response
     */
    public function index() {
        $data = $this->repository->getAllLatestNews ();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::latestnews.showallError' ) );
    }
    
    /**
     * Function to add new Latest News
     * 
     * @param Request $request 
     * @return \Contus\Base\response
     */
    public function store(Request $request) {
        $save = $this->repository->addOrUpdateLatestNews ();
        return ($save === 1) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::latestnews.add.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::latestnews.add.error' ) );
    }
    
    /**
     * function to get one Latest News
     * 
     * @param Request $request 
     * @param int $contentId 
     * @return \Contus\Base\response
     */
    public function show(Request $request, $contentId) {
        $data = $this->repository->getLatestNews ( $contentId );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::latestnews.showError' ) );
    }
    
    /**
     * function to update one Latest News
     * 
     * @param Request $request 
     * @param int $contentId 
     * @return \Contus\Base\response
     */
    public function update(Request $request, $contentId) {
        $update = $this->repository->addOrUpdateLatestNews ( $contentId );
        return ($update === 1) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::latestnews.update.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::latestnews.update.error' ) );
    }
    
    /**
     * function to delete one Latest News
     * 
     * @param Request $request 
     * @param int $contentId 
     * @return \Contus\Base\response
     */
    public function destroy(Request $request, $contentId) {
        $data = $this->repository->deleteLatestNews ( $contentId );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::latestnews.delete.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::latestnews.delete.error' ) );
    }
}
