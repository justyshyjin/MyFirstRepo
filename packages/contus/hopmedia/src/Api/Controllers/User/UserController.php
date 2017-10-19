<?php

namespace Contus\Hopmedia\Api\Controllers\User;

use Illuminate\Http\Request;
use Contus\Hopmedia\Repositories\UserRepository;
use Contus\Base\Repositories\UploadRepository;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Contus\Cms\Repositories\EmailTemplatesRepository;
use Auth;

class UserController extends ApiController {
    
    /**
     * class property to hold the instance of UploadRepository
     *
     * @var \Contus\Base\Repositories\UploadRepository
     */
    public $uploadRepository;
    /**
     * Construct method
     */
    public function __construct(UserRepository $UserRepository, UploadRepository $uploadRepository) {
        parent::__construct ();
        $this->repository = $UserRepository;
        $this->uploadRepository = $uploadRepository;
    }
    
    /**
     * To get the user info.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo() {
        return $this->getSuccessJsonResponse ( [ 'info' => [ 'rules' => $this->repository->getRules (),'allUserGroups' => $this->adminUserGroupRepository->getAllUserGroups () ] ] );
    }
    
    /**
     * To get the changepassword info.
     *
     * @return \Illuminate\Http\Response
     */
    public function getChangePasswordInfo() {
        return $this->getSuccessJsonResponse ( [ 'rules' => $this->repository->setRules ( [ 'old_password' => 'required','password' => 'required|confirmed','password_confirmation' => 'required|same:password' ] )->getRules () ] );
    }
    
    /**
     * Merchant change password
     *
     * @return \Illuminate\Http\Response
     */
    public function postChangepassword() {
        $isCreated = false;
        
        if ($this->repository->changePassword ()) {
            $isCreated = true;
            $this->request->session ()->flash ( StringLiterals::SUCCESS, trans ( 'user::adminuser.changepassword.success' ) );
        }
        
        return ($isCreated) ? $this->getSuccessJsonResponse ( [ ], trans ( 'user::adminuser.changepassword.success' ) ) : $this->getErrorJsonResponse ( [ ], trans ( 'user::adminuser.changepassword.incorrect' ) );
    }
    
    /**
     * Store a newly created broadcaster user.
     *
     * @return \Illuminate\Http\Response
     */
    public function postAdd() { 
        $isCreated = false;
        
        if ($this->repository->addOrUpdateUsers ()) {
            $isCreated = true;
            $this->request->session ()->flash ( StringLiterals::SUCCESS, trans ( 'hopmedia::hopmedia.message.register-success' ) );
        }
        
        return ($isCreated) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'hopmedia::hopmedia.message.register-success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'hopmedia::hopmedia.message.register-error' ) );
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param int $id 
     *
     * @return \Illuminate\Http\Response
     */
    public function postEdit($id) {
        $isCreated = false;
        if ($this->repository->addOrUpdateUsers ( $id )) {
            $isCreated = true;
            $this->request->session ()->flash ( 'success', trans ( 'user::adminuser.updated' ) );
        }
     return ($isCreated) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'user::adminuser.updated' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'user::adminuser.updatedError' ) );
     }
    
    /**
     * Controller function to delete profile image of a user.
     *
     * @param integer $id
     * The id of the user.
     * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
     */
    public function postDeleteProfileImage($id) { 
        $isProfileImageDeleted = false;
        try {
            /**
             * Call the deleteProfileImage repository method to delete profile image of a user.
             */
            if ($this->repository->deleteProfileImage ( $id )) { 
                $isProfileImageDeleted = true;
                $this->request->session ()->flash ( StringLiterals::SUCCESS, trans ( 'user::user.message.profile-image-delete-success' ) );
            }
        }
        catch ( Exception $e ) {
            /**
             * Handle the error exception when the user of the profile image does not exist.
             */
            $this->request->session ()->flash ( StringLiterals::ERROR, trans ( 'user::user.user_not_exist' ) );
            $isProfileImageDeleted = true;
        }
        /**
         * If the profile image is deleted successfully, return the success response.
         * If the profile image is not deleted successfully, return the failure resposne.
         */
        return ($isProfileImageDeleted) ? $this->getSuccessJsonResponse ( [ StringLiterals::MESSAGE => trans ( 'user::user.message.profile-image-delete-success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'user::user.message.profile-image-delete-error' ) );
    }
    
    /**
     * To get the data to edit
     *
     * @param int $id 
     *
     * @return \Illuminate\Http\Response
     */
    public function getEdit() {
        $getUserData = $this->repository->getUser ( $this->auth->user ()->id );
        
        return (is_null ( $getUserData )) ? $this->getErrorJsonResponse ( [ ], null, 404 ) : $this->getSuccessJsonResponse ( [ 'response' => $getUserData ] );
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }
    
    /**
     * Display the specified resource.
     *
     * @param int $id 
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id 
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request 
     * @param int $id 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id 
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
    /**
     * Update default grid view for logged in user
     *
     * @param int $option 
     * @return \Illuminate\Http\Response
     */
    public function getUpdategridview($option) {
        $isSaved = false;
        $this->auth->user ()->default_grid_view = $option;
        if ($this->auth->user ()->save ()) {
            $isSaved = true;
        }
        return ($isSaved) ? $this->getSuccessJsonResponse ( [ ], trans ( 'base::general.updated' ) ) : $this->getErrorJsonResponse ( [ ], trans ( 'base::general.updated_error' ), 403 );
    }
    /**
     * Check user email is unique
     *
     * @param int $id 
     * @return \Illuminate\Http\Response
     */
    public function getUnique($id = null) {
        $isUnique = $this->repository->isUniqueUserEmail ( $id );
        return ($isUnique) ? $this->getSuccessJsonResponse ( [ ], 'Success' ) : $this->getErrorJsonResponse ( [ ], 'Failed' );
    }
    
    /**
     * Upload the profile image
     *
     * @param string $modelIdentifier 
     * @return Response
     */

    public function postProfileImage() { 
        $tempImageInfo = $this->uploadRepository->setModelIdentifier ( UploadRepository::MODEL_IDENTIFIER_PROFILE )->tempPrepare ()->tempUpload (); 
        return empty ( $tempImageInfo ) ? $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.messsage.unable_to_upload' ) ) : $this->getSuccessJsonResponse ( [ 'info' => array_shift ( $tempImageInfo ) ] );
    }
}
