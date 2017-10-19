<?php

/**
 * Groups Controller
 *
 * To manage the Exam Groups such as create, edit and delete
 *
 * @name Groups Controller
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Api\Controllers\Admin;

use Contus\Base\ApiController;
use Contus\Video\Repositories\CategoryRepository;
use Contus\Video\Repositories\GroupRepository;
use Contus\Video\Repositories\CollectionRepository;

class GroupController extends ApiController {
    /**
     * Constructer method which defines the objects of the classes used.
     *
     * @param object $GroupRepository
     */
    public function __construct(GroupRepository $groupRepository,CollectionRepository $category,GroupRepository $grouprepositary) {
      
        parent::__construct ();
        $this->repository = $groupRepository;
        $this->collection = $category;
        $this->group = $grouprepositary;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
    }
    /**
     * Function to assign videos to Group
     *
     * @return \Contus\Base\response
     */
    public function postAdd() {
        $save = $this->repository->addOrUpdateGroup ();
        return ($save === true) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::playlist.added' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.addederror' ) );
    }
    
    /**
     * Function to eidt the groups exams
     *
     * @return \Contus\Base\response
     */
    public function postEdit($id) {
        $save = $this->repository->addOrUpdateGroup ($id);
        return ($save === true) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::playlist.added' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.addederror' ) );
    }
    /**
     * Function to delete videos from Group
     *
     * @return \Contus\Base\response
     */
    public function postDelete() {
        $save = $this->repository->deletePlaylistVideos ();
        return ($save === true) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::playlist.removed' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::playlist.removederror' ) );
    }

    /**
     * get Information for create form
     * return various information request by the form
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo() {
        $subcategory = $this->collection->getAllCollectionName();
        return $this->getSuccessJsonResponse ( [ 'info' => [ 'locale' => trans ( 'validation' ),'isActive' => [ 'In-active','Active' ],'category'=>$subcategory, ] ] );   
    }

    /**
     * Get Group related exams videos
     * return various information request by the form
     *
     * @return \Illuminate\Http\Response
     */
    public function getVideoCollections($id) {
        $subcategory = $this->group->getAllVideos($id);
        return $this->getSuccessJsonResponse ( [ 'message' => $subcategory ] );
    }
}
