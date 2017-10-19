<?php

/**
 * Groups Controller
 *
 * To manage the Exams such as create, edit and delete
 *
 * @name       Groups Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Http\Controllers\Admin;

use Contus\Base\Controller as BaseController;

class GroupsController extends BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\View
   */
  public function getIndex() {
    return view ( 'video::admin.groups.index');
  }

  /**
   * get Grid template
   *
   * @return \Illuminate\Http\View
   */
  public function getGridlist() {
    return view ( 'video::admin.groups.gridView' );
  }
  /**
   * Controller function to get the Group videos.
   *
   * @param integer $id The id of the group.
   * @return Ambigous <\Contus\Base\response, \Illuminate\Http\JsonResponse>
   */
  public function getVideos($id) {
      return view ( 'video::admin.groups.videos', [
              'id' => $id
      ] );
  }
}
