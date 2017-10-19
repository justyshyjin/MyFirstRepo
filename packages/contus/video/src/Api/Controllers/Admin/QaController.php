<?php

/**
 * Qa Controller
 *
 * To manage the Qa such as update status
 *
 * @name Qa Controller
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Video\Repositories\QuestionsRepository;

class QaController extends ApiController {
    /**
     * Constructer method which defines the objects of the classes used.
     *
     * @param object $qaRepository
     */
    public function __construct(QuestionsRepository $QaRepository) {

        parent::__construct ();
        $this->repository = $QaRepository;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
    }
    /**
     * function to update status from admin question
     *
     * @param int $qid
     * @return json
     */
    public function postUpdateAdmin($qid){
        $status = '';
        if($this->request->has('status')){
            $status = $this->request->status;
        }
        $save = $this->repository->updateStatus ($qid,$status);
        return ($save) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.qaupdated' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.qaupdatederror' ) );
    }
    /**
     * function to update status from grid
     *
     * @param int $id
     * @return json
     */
    public function postUpdateStatus($qid) {
        return $this->postUpdateAdmin($qid);
    }

    /**
     * get Information for create form
     * return various information request by the form
     *
     * @return json
     */
    public function getInfo() {
        $getQa = $this->repository->getAllQa();
        return $this->getSuccessJsonResponse ( [ 'info' => [ 'locale' => trans ( 'validation' ),'isActive' => [ 'In-active','Active' ],'Qa'=>$getQa, ] ]);
    }
}
