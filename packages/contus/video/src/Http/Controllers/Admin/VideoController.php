<?php

/**
 * VideoController
 *
 * @name       VideoController
 * @vendor     Contus
 * @package    Video
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Http\Controllers\Admin;

use Contus\Base\Controller as BaseController;
use Contus\Video\Repositories\VideoRepository;
use Contus\Video\Repositories\CollectionRepository;
use Contus\Video\Repositories\CategoryRepository;
use Contus\Base\Helpers\StringLiterals;
use Illuminate\Http\Request;

class VideoController extends BaseController {

 public $videoRepository;
 /**
  * class property is used to initiate the class
  *
  * @vendor Contus
  *
  * @package Video
  * @var array
  */
 public function __construct(VideoRepository $videoRepository, CollectionRepository $collectionRepository, CategoryRepository $categoryRepository) {
  $this->videoRepository = $videoRepository;
  $this->collectionRepository = $collectionRepository;
  $this->categoryRepository = $categoryRepository;
 }
 
 /**
  * Show the videos page
  *
  * @vendor Contus
  *
  * @package Video
  * @return \Illuminate\Http\View
  */
 public function getIndex(Request $request) {
  if (isset($_REQUEST['grid'])) { 
    $grid = $_REQUEST['grid'];
  } else {
    $grid = "video";  
  }
  if($request->segment(2) == 'livevideos') {
      $view = 'video::admin.livevideos.index';
  }else{
      $view = 'video::admin.videos.index';
  }
  return view ( $view, [
      'region' => config ()->get ( 'settings.aws-settings.aws-general.aws_region' ),
      'key' => config ()->get ( 'settings.aws-settings.aws-general.aws_key' ),
      'secret' => config ()->get ( 'settings.aws-settings.aws-general.aws_secret' ),
      's3_bucket' => config ()->get ( 'settings.aws-settings.aws-general.aws_s3_bucket' ),
      'developer_key' => config ()->get ( 'settings.google.google-general.developer_key' ), 
      'client_id' => config ()->get ( 'settings.google.google-general.client_id' ),
      'grid' => $grid,
  ] );
 }
 /**
  * get Grid template
  *
  * @return \Illuminate\Http\View
  */
 public function getGridlist() {
  return view ( 'video::admin.videos.gridView' );
 }
 /**
  * get wowza from template
  *
  * @return \Illuminate\Http\View
  */
 public function getAdd() {
     return view ( 'video::admin.videos.add' );
 }
 
 /**
  * get wowza from template
  *
  * @return \Illuminate\Http\View
  */
 public function postAdd() {
     dd('postadd');
 }
 /**
  * get Detail video view and edit template
  *
  * @return \Illuminate\Http\View
  */
  public function getDetailsVideoEdit($id) {
    return view ( 'video::admin.videos.detailVideo' ,[ 
      'id' => $id 
    ]);
  } 
  /**
  * Show Video Details in to video details page
  *
  * @return \Illuminate\Http\View
  */
  public function getViewDetailsVideo($id) {

    $redirectViewVideoDetail = 'video::admin.videos.viewDetailVideo';
    if (isset($_REQUEST['collectionId'])) { 
      $this->breadcrumb = $this->collectionRepository->getCollection($_REQUEST['collectionId']);
      return view ( $redirectViewVideoDetail ,[ 
        'id' => $id,
        'collection_title' => $this->breadcrumb->title 
      ]); 
    } else if (isset($_REQUEST[StringLiterals::VIDEOCATEGORYID])) {
      $this->breadcrumb = $this->categoryRepository->getCategory($_REQUEST[StringLiterals::VIDEOCATEGORYID]);
      return view ( $redirectViewVideoDetail ,[ 
        'id' => $id,
        'category_title' => $this->breadcrumb->title,
        StringLiterals::VIDEOCATEGORYID => $_REQUEST[StringLiterals::VIDEOCATEGORYID],
      ]); 
    } else {
      return view ( $redirectViewVideoDetail ,[ 
        'id' => $id,
      ]);      
    }
  } 
}