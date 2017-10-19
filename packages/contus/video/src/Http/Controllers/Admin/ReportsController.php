<?php

/**
 * DashboardControler
 *
 * @name       DashboardControler
 * @vendor     Contus
 * @package    Video
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Video\Http\Controllers\Admin;

use Contus\Base\Controller as BaseController;

class ReportsController extends BaseController {

   /**
   * class property is used to initiate the class
   *
   * @vendor     Contus
   * @package    Video
   * @var array
   */
    public function __construct() {
    }

    /**
    * Show the dashboard page
    *
    * @vendor     Contus
    * @package    Video
    * @return \Illuminate\Http\View
    */
    public function getIndex() {
        return view ( 'video::admin.reports.reports' );
    }
}