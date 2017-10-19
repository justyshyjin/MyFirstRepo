<?php
/**
 * Category Controller
 *
 * To manage the video categories.
 *
 * @name       Category Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Api\Controllers\Admin;

use Illuminate\Http\Request;
use Contus\Video\Repositories\CategoryRepository;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;

class CategoryController extends ApiController {
  /**
   * class property to hold the instance of UploadRepository
   *
   * @var \Contus\Base\Repositories\UploadRepository
   */
  public $uploadRepository;
  /**
   * Construct method
   */
  public function __construct(CategoryRepository $categoryRepository, UploadRepository $uploadRepository) {
    parent::__construct ();
    $this->repository = $categoryRepository;
    $this->uploadRepository = $uploadRepository;
  }
  /**
   * get Information for create form
   * return various information request by the form
   * request will be having query param which refer to category
   *
   * @return \Illuminate\Http\Response
   */
  public function getAdd() {
    return $this->getSuccessJsonResponse ( [
        StringLiterals::RULES => $this->repository->getRules (),
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
    $addCategory = $this->repository->addOrUpdateCategory ();

    $isCategoryAdd = false;
    if ($addCategory) {
      $isCategoryAdd = true;
      $this->request->session ()->flash ( StringLiterals::SUCCESS, trans ( 'video::categories.added' ) );
    }
    return ($isCategoryAdd) ? $this->getSuccessJsonResponse ( [
        StringLiterals::STATUS => 'success',
        StringLiterals::MESSAGE => trans ( 'video::categories.success.added' )
    ] ) : $this->getErrorJsonResponse ( [
        [
            StringLiterals::STATUS => 'error',
            StringLiterals::MESSAGE => trans ( 'video::categories.error.added' )
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
    $getCategory = $this->repository->getCategory ( $id );
    return (is_null ( $getCategory )) ? $this->getErrorJsonResponse ( [ ], null, 404 ) : $this->getSuccessJsonResponse ( [
        'response' => $getCategory,
        StringLiterals::RULES => $this->repository->getrules ()
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
    $editCategory = $this->repository->addOrUpdateCategory ($id);

    $isCategoryEdit = false;
    if ($editCategory) {
      $isCategoryEdit = true;
      $this->request->session ()->flash ( StringLiterals::SUCCESS, trans ( 'video::categories.updated' ) );
    }
    return ($isCategoryEdit) ? $this->getSuccessJsonResponse ( [
        StringLiterals::STATUS => 'success',
        StringLiterals::MESSAGE => trans ( 'video::categories.success.updated' )
    ] ) : $this->getErrorJsonResponse ( [
        [
            StringLiterals::STATUS => 'error',
            StringLiterals::MESSAGE => trans ( 'video::categories.error.updated' )
        ]
    ] );
  }
  /**
   * Upload the image for a category.
   *
   * @param string $modelIdentifier
   * @return Response
   */
  public function postCategoryImage() {
      $tempImageInfo = $this->uploadRepository->setModelIdentifier ( UploadRepository::MODEL_IDENTIFIER_CATEGORY_IMAGE )->tempPrepare ()->tempUpload ();

      return empty ( $tempImageInfo ) ? $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.messsage.unable_to_upload' ) ) : $this->getSuccessJsonResponse ( [
              'info' => array_shift ( $tempImageInfo )
              ] );
  }
  /**
   * Controller function to delete image of a category.
   *
   * @param integer $id The id of the category.
   * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
   */
  public function postDeleteCategoryImage($id) {
      $isImageDeleted = false;

      try {
          /**
           * Call the deleteCategoryImage repository method to delete image of a category.
           */
          if ($this->repository->deleteCategoryImage ( $id )) {
              $isImageDeleted = true;
              $this->request->session ()->flash ( StringLiterals::SUCCESS, trans ( 'video::categories.message.image-delete-success' ) );
          }
      } catch ( Exception $e ) {
          /**
           * Handle the error exception when the category of the image does not exist.
           */
          $this->request->session ()->flash ( StringLiterals::ERROR, trans ( 'video::categories.category_not_exist' ) );
          $isImageDeleted = true;
      }
      /**
       * If the image of the category is deleted successfully, return the success response.
       * If the image of the category is not deleted successfully, return the failure resposne.
       */
      return ($isImageDeleted) ? $this->getSuccessJsonResponse ( [
              StringLiterals::MESSAGE => trans ( 'video::categories.message.image-delete-success' )
              ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'video::categories.message.image-delete-error' ) );
  }
  /**
   * Controller function to get the parent category.
   *
   * @param integer $id The id of the category.
   * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
   */
  public function postParentCategory($id) {
    $getParentCategory = $this->repository->getParentCategory ( $id );
    return (is_null ( $getParentCategory )) ? $this->getErrorJsonResponse ( [ ], null, 404 ) : $this->getSuccessJsonResponse ( [
        'parentCategory' => $getParentCategory,
    ] );
  }

  /**
   * Controller function to get the category related videos.
   *
   * @param integer $id The id of the category.
   * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
   */
  public function getVideoCategories($id) {
    $getVideoCategories = $this->repository->getVideoCategories ( $id );
    return (is_null ( $getVideoCategories )) ? $this->getErrorJsonResponse ( [ ], null, 404 ) : $this->getSuccessJsonResponse ( [
        'videoCategories' => $getVideoCategories,
    ] );
  }

  /**
   * Controller function to get the parent category.
   *
   * @param integer $id The id of the category.
   * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
   */
  public function postChildCategory($id) {
    $getChildCategory = $this->repository->getCategoryWithChild ( $id );
    return (is_null ( $getChildCategory )) ? $this->getErrorJsonResponse ( [ ], null, 404 ) : $this->getSuccessJsonResponse ( [
        'childCategory' => $getChildCategory,
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
                      StringLiterals::RULES => $this->repository->getRules (),
                      'locale' => trans ( 'validation' ),
                      'isActive' => [
                          'In-active',
                          'Active'
                      ],
              ]
      ] );
  }
  /**
   * Function to get parent categories, active categories and number of active presets.
   *
   * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
   */
  public function getUpdatedDetails() {
      return $this->getSuccessJsonResponse ( [
              'allCategoriesHTML' => $this->repository->getAllCategoryList(),
      ] );
  }

  /**
   * Check categories name is unique an give the responce in json format
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function getCategoriesUnique($id = null) {
      $isCategoriesUnique = $this->repository->isUniqueCategories ( $id );
      return ($isCategoriesUnique) ? $this->getSuccessJsonResponse ( [ ],'Success'  ) : $this->getErrorJsonResponse ( [ ], 'Failed' );
  }
}
