<?php

/**
 * Collection Controller
 *
 * To manage the Collection such as create, edit and delete
 *
 * @name       Collection Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Api\Controllers\Admin;

use Illuminate\Http\Request;
use Contus\Video\Repositories\CollectionRepository;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;

class CollectionController extends ApiController {
  /**
   * Construct method
   */
  public function __construct(CollectionRepository $collectionRepository) {
    parent::__construct ();
    $this->repository = $collectionRepository;
  }
  /**
   * get Information for create form
   * return various information request by the form
   * request will be having query param which refer to category
   *
   * @return \Illuminate\Http\Response
   */
  public function getAll() {
    return $this->getSuccessJsonResponse ( [ 
        StringLiterals::RULES => $this->repository->getRules (),
        'allCollection' => $this->repository->getAllCollection (),
    ] );
  }

  /**
   * Add the specified resource in storage.
   *
   * @param int $id          
   *
   * @return \Illuminate\Http\Response
   */
  public function postAdd() {
    $addCollection = $this->repository->addOrUpdateCollection ();

    $isCollectionAdd = false;
    if ($addCollection) {
      $isCollectionAdd = true;
      $this->request->session ()->flash ( StringLiterals::SUCCESS, trans ( StringLiterals::COLLECTION_ADDED_TRANS ) );
    }
    return ($isCollectionAdd) ? $this->getSuccessJsonResponse ( [ 
        StringLiterals::STATUS => StringLiterals::SUCCESS,
        StringLiterals::MESSAGE => trans ( 'video::collection.success.added' ) 
    ] ) : $this->getErrorJsonResponse ( [ 
        [ 
            StringLiterals::SUCCESS => StringLiterals::ERROR,
            StringLiterals::MESSAGE => trans ( 'video::collection.error.added' ) 
        ] 
    ] );
  }

   /**
   * Create a collection
   * @return \Illuminate\Http\Response
   */
  public function postCreateCollection() {
    $createCollection = $this->repository->createCollection ();

    $isCollectionCreate = false;
    if ($createCollection) {
      $isCollectionCreate = true;
      $this->request->session ()->flash ( StringLiterals::SUCCESS, trans ( StringLiterals::COLLECTION_ADDED_TRANS ) );
    }
    return ($isCollectionCreate) ? $this->getSuccessJsonResponse ( [ 
        StringLiterals::STATUS => StringLiterals::SUCCESS,
        StringLiterals::MESSAGE => trans ( StringLiterals::COLLECTION_ADDED_TRANS ) 
    ] ) : $this->getErrorJsonResponse ( [ 
        [ 
            StringLiterals::SUCCESS => StringLiterals::ERROR,
            StringLiterals::MESSAGE => trans ( 'video::collection.error.added' ) 
        ] 
    ] );
  }
  /**
   * get Information for create form
   * return various information request by the form
   * request will be having query param which refer to category
   *
   * @return \Illuminate\Http\Response
   */
  public function getEdit($id) {
    $getCollection = $this->repository->getCollection ( $id );
    return (is_null ( $getCollection )) ? $this->getErrorJsonResponse ( [ ], null, 404 ) : $this->getSuccessJsonResponse ( [ 
        'response' => $getCollection,
        StringLiterals::RULES => $this->repository->getEditRules () 
    ] );
  }

  /**
   * Add the specified resource in storage.
   *
   * @param int $id          
   *
   * @return \Illuminate\Http\Response
   */
  public function postEdit($id) {
    $editCollection = $this->repository->addOrUpdateCollection ($id);

    $isCollectionEdit = false;
    if ($editCollection) {
      $isCollectionEdit = true;
      $this->request->session ()->flash ( StringLiterals::SUCCESS, trans ( 'video::collection.updated' ) );
    }
    return ($isCollectionEdit) ? $this->getSuccessJsonResponse ( [ 
        StringLiterals::SUCCESS => StringLiterals::SUCCESS,
        StringLiterals::MESSAGE => trans ( 'video::collection.success.updated' ) 
    ] ) : $this->getErrorJsonResponse ( [ 
        [ 
            StringLiterals::SUCCESS => StringLiterals::ERROR,
            StringLiterals::MESSAGE => trans ( 'video::collection.error.updated' ) 
        ] 
    ] );
  }
  /**
   * get add collection rules
   *
   * @return \Illuminate\Http\Response
   */
  public function getAddRules() {
    return $this->getSuccessJsonResponse ( [ 
        StringLiterals::RULES => $this->repository->getEditRules () 
    ] );
  }
  
  /**
   * get Information for create form
   * return various information request by the form
   *
   * @return \Illuminate\Http\Response
   */
  public function getInfo() {
      return $this->getSuccessJsonResponse ( [
              'info' => [
                      'rules' => $this->repository->getEditRules (),
                      'locale' => trans ( 'validation' ),
                      'isActive' => [
                              'In-active',
                              'Active'
                      ],
              ]
      ] );
  }
  
  /**
   * Check collection name is unique
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function getCollectionUnique($id = null) {
      $isUnique = $this->repository->isUniqueCollection ( $id );
      return ($isUnique) ? $this->getSuccessJsonResponse ( [ ],'Success'  ) : $this->getErrorJsonResponse ( [ ], 'Failed' );
  }
  /**
   * Controller function to get the collection related videos.
   *
   * @param integer $id The id of the collection.
   * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
   */
  public function getVideoCollections($id) {
      $getVideoCollections = $this->repository->getVideoCollections ( $id );
      return (is_null ( $getVideoCollections )) ? $this->getErrorJsonResponse ( [ ], null, 404 ) : $this->getSuccessJsonResponse ( [
              'videoCollections' => $getVideoCollections,
      ] );
  }
  /**
   * Function to remove video from collection
   *
   * @see \Contus\Base\ApiController::postAction()
   * @return \Illuminate\Http\Response
   */
  public function postDeleteCollectionVideos() {
      if ($this->request->has ( StringLiterals::SELECTED_CHECKBOX ) && is_array ( $this->request->get ( StringLiterals::SELECTED_CHECKBOX ) )) {
          $isActionCompleted = $this->repository->removeVideoFromCollection ( $this->request->input ( StringLiterals::SELECTED_CHECKBOX ), $this->request->input ( 'collectionId' ) );
          return $isActionCompleted ? $this->getSuccessJsonResponse ( [ ], trans ( 'base::general.success_delete' ) ) : $this->getErrorJsonResponse ( [ ], trans ( StringLiterals::INVALID_REQUEST_TRANS ), 403 );
      }
  }
}
