<?php

namespace Contus\User\Api\Controllers\Admin;

use Illuminate\Http\Request;
use Contus\User\Repositories\AdminUserGroupRepository;
use Contus\Base\Repositories\UploadRepository;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Auth;

class AdminUserGroupController extends ApiController {
    
    /**
     * class property to hold the instance of UploadRepository
     *
     * @var \Contus\Base\Repositories\UploadRepository
     */
    public $uploadRepository;
    /**
     * Construct method
     */
    public function __construct(AdminUserGroupRepository $adminUserGroupRepository, UploadRepository $uploadRepository) {
        parent::__construct ();
        $this->repository = $adminUserGroupRepository;
        $this->uploadRepository = $uploadRepository;
    }
    public function getUserGroup() {
        $getUserGroup = $this->repository->getAllUserGroups ();
        
        return (is_null ( $getUserGroup )) ? $this->getErrorJsonResponse ( [ ], null, 404 ) : $this->getSuccessJsonResponse ( [ 'response' => $getUserGroup ] );
    }
}