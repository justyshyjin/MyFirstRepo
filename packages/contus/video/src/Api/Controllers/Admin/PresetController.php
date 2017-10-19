<?php

/**
 * Preset Controller
 *
 * To manage the video presets.
 *
 * @name       Preset Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Api\Controllers\Admin;

use Illuminate\Http\Request;
use Contus\Video\Repositories\PresetRepository;
use Contus\Base\ApiController;

class PresetController extends ApiController {
 public $presetRepository;
 
 /**
  * Constructer method which defines the objects of the classes used.
  *
  * @param object $videosRepository
  *         The object of VideoRepository class
  */
 public function __construct(PresetRepository $presetRepository) {
  parent::__construct ();
  $this->repository = $presetRepository;
  $this->repository->setRequestType ( static::REQUEST_TYPE );
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
                     'locale' => trans ( 'validation' ),
                     'isActive' => [
                             'In-active',
                             'Active'
                     ],
                     'numberOfActivePresets' => $this->repository->getNumberOfActivePresets (),
             ]
     ] );
 }
}
