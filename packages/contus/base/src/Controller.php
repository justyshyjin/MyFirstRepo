<?php

/**
 * Base Controller
 *
 * @name       Controller
 * @vendor     Contus
 * @package    Base
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Base;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Exception;
use Contus\Base\Helpers\StringLiterals;

abstract class Controller extends BaseController {
 use AuthorizesRequests, DispatchesJobs;
 
 /**
  * Class constants for holding request type handled by child controllers
  *
  * @vendor Contus
  *
  * @package Base
  * @var const
  */
 const REQUEST_TYPE = 'HTTP';
 /**
  * The request registered on Base Controller.
  *
  * @vendor Contus
  *
  * @package Base
  * @var object
  */
 protected $request;
 /**
  * The auth registered on Base Controller.
  *
  * @vendor Contus
  *
  * @package Base
  * @var object
  */
 protected $auth;
 /**
  * The class property to hold the logger object
  *
  * @vendor Contus
  *
  * @package Base
  * @var object
  */
 protected $logger;
 /**
  * The class property to hold the logger object
  *
  * @vendor Contus
  *
  * @package Base
  * @var object
  */
 protected $repository;
 /**
  * class property to hold the setting cache data
  *
  * @vendor Contus
  *
  * @package Base
  * @var array
  */
 
 /**
  * class property to hold the setting cache data
  *
  * @vendor Contus
  *
  * @package Base
  * @var array
  */
 public function __construct() {
  $this->request = app ()->make ( 'request' );
  $this->auth = app ()->make ( 'auth' );
  $this->logger = app ()->make ( 'log' );
 }
 /**
  * Make failure response for ajax
  * log the error messsage if logger property exist
  *
  * @vendor Contus
  *
  * @package Base
  * @param Exception $e         
  * @param int $statusCode         
  * @param boolean $withMessage         
  * @return response
  */
 protected function makeFailureResponseForAjax(Exception $e, $statusCode = 500, $withMessage = false) {
  if (property_exists ( $this, 'logger' )) {
   $this->logger->error ( $e->getMessage () );
  }
  
  return response ()->json ( $withMessage ? [ 
      StringLiterals::MESSAGE => $e->getMessage () 
  ] : [ ], $statusCode );
 }
 /**
  * get success json response
  *
  * @vendor Contus
  *
  * @package Base
  * @param array $data         
  * @param string $message         
  * @param int $statusCode         
  * @return response
  */
 protected function getSuccessJsonResponse(array $data = [], $message = null, $statusCode = 200) {
  return response ()->json ( array_merge ( [ 
      StringLiterals::ERROR => false,
      'statusCode' => $statusCode,
      'status' => 'success',
      StringLiterals::MESSAGE => $message 
  ], $data ), $statusCode );
 }
 /**
  * get error json response
  *
  * @vendor Contus
  *
  * @package Base
  * @param array $data         
  * @param string $message         
  * @param int $statusCode         
  * @return response
  */
 protected function getErrorJsonResponse(array $data = [], $message = null, $statusCode = 500) {
  return response ()->json ( array_merge ( [ 
      StringLiterals::ERROR => true,
      'statusCode' => $statusCode,
      'status' => StringLiterals::ERROR,
      StringLiterals::MESSAGE => $message 
  ], $data ), $statusCode );
 }
}
