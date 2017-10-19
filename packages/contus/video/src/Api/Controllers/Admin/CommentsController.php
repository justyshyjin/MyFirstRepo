<?php

/**
 * Comments Controller
 *
 * To manage the Comments such as update status
 *
 * @name Comments Controller
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Video\Repositories\CommentsRepository;

class CommentsController extends ApiController {
    /**
     * Constructer method which defines the objects of the classes used.
     *
     * @param object $commentsRepository
     */
    public function __construct(CommentsRepository $commentsRepository) {

        parent::__construct ();
        $this->repository = $commentsRepository;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
    }
    /**
     * function to update status from admin comments
     *
     * @param int $id
     * @return json
     */
    public function postUpdateAdmin($id){
        $status = '';
        if($this->request->has('status')){
            $status = $this->request->status;
        }
        $save = $this->repository->updateStatus ($id,$status);
        return ($save) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.commentupdated' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.commentupdatederror' ) );
    }
    /**
     * function to update status from admin grid
     *
     * @param int $id
     * @return json
     */
    public function postUpdateStatus($id) {
        return $this->postUpdateAdmin($id);
    }

    /**
     * get Information for create form
     * return various information request by the form
     *
     * @return json
     */
    public function getInfo() {
        $subcategory = $this->repository->getAllComments();
        return $this->getSuccessJsonResponse ( [ 'info' => [ 'locale' => trans ( 'validation' ),'isActive' => [ 'In-active','Active' ],'comments'=>$subcategory, ] ]);
    }
}
