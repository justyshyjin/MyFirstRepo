<?php

/**
 * Customer User Controller
 *
 * To manage the Admin users such as create, edit and delete the admin users
 *
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Customer\Http\Controllers\Customer;

use Contus\Base\Controller as BaseController;
use Contus\Customer\Repositories\CustomerRepository;

class CustomerUserController extends BaseController {
  /**
   * Construct method
   */
  public function __construct(CustomerRepository $customerRepository) {
    parent::__construct ();
    $this->_repository = $customerRepository;
    $this->_repository->setRequestType ( static::REQUEST_TYPE );
  }
  
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\View
   */
  public function getIndex($status = 'all') {
    return view ( 'customer::admin.customer.index');
  }
  /**
   * get Grid template
   *
   * @return \Illuminate\Http\View
   */
  public function getGridlist() {
    return view ( 'customer::admin.customer.gridView' );
  }
}
