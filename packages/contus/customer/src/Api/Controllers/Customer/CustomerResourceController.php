<?php
/**
 * Customer Resource Repository
 *
 * To manage the functionalities related to the Customer REST api methods
 *
 * @name CustomerResourceController
 * @vendor Contus
 * @package Customer
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Customer\Api\Controllers\Customer;

use Illuminate\Http\Request;
use Contus\Base\ApiController;
use Contus\Customer\Repositories\CustomerRepository;

class CustomerResourceController extends ApiController {

    /**
     * class property to hold the instance of UploadRepository
     *
     * @var \Contus\Base\Repositories\UploadRepository
     */
    public $uploadRepository;

    /**
     * Construct method
     */
    public function __construct(CustomerRepository $customerRepository) {  
        parent::__construct (); 
        $this->repository = $customerRepository;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
    }

    /**
     * Funtion to list all the customers using pagenation
     *
     * @return \Contus\Base\response
     */
    public function index() { 
        $data = $this->repository->getAllCustomers();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::customer.showallError' ) );
    }

    /**
     * Function to add new customers
     *
     * @param Request $request
     * @return \Contus\Base\response
     */
    public function store(Request $request) {
     $save = $this->repository->addOrUpdateCustomers ();
     return (isset($save)) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'customer::customer.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::customer.error' ) );
    }
    /**
     * Function to add new customers
     *
     * @param Request $request
     * @return \Contus\Base\response
     */
    public function addSubcription() {
     $save = $this->repository->addSubscription();
     return (isset($save)) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'customer::customer.subscription_success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::customer.subscription_error' ) );
    }
    
    /**
     * function to update one customer information
     *
     * @param Request $request
     * @param int $customerId
     * @return \Contus\Base\response
     */
    public function updateCustomer(Request $request, $customerId) {
     $update = $this->repository->addSubscription();
     return (isset($update)) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'customer::customer.updated' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::customer.updatedError' ) );
    }
    
    /**
     * function to get one customer information
     *
     * @param Request $request
     * @param int $customerId
     * @return \Contus\Base\response
     */
    public function show(Request $request, $customerId) {
        $data = $this->repository->getCustomer ( $customerId );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [ 'data' => $data ], trans ( 'customer::customer.showError' ) );
    }

    /**
     * function to update one customer information
     *
     * @param Request $request
     * @param int $customerId
     * @return \Contus\Base\response
     */
    public function update(Request $request, $customerId) { 
     $update = $this->repository->addOrUpdateCustomers($customerId); 
        return (isset($update->id)) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'customer::customer.updated' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::customer.updatedError' ) );
    }

    /**
     * function to delete one customer information
     *
     * @param Request $request
     * @param int $customerId
     * @return \Contus\Base\response
     */
    public function destroy(Request $request, $customerId) {
        $data = $this->repository->deleteCustomer ( $customerId );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'customer::customer.deleted' )] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::customer.deletedError' ) );
    }
}
