<?php
/**
 * subscription Resource Repository
 *
 * To manage the functionalities related to the subscription REST api methods
 *
 * @name SubscriptionResourceController
 * @vendor Contus
 * @package customer
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Customer\Api\Controllers\Customer;

use Illuminate\Http\Request;
use Contus\Base\ApiController;
use Contus\Customer\Repositories\SubscriptionRepository;

class SubscriptionResourceController extends ApiController {

    /**
     * Construct method
     */
    public function __construct(SubscriptionRepository $subscriptionRepository) {
        parent::__construct ();
        $this->repository = $subscriptionRepository;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
    }

    /**
     * Funtion to list all the subscriptions using pagenation
     *
     * @return \Contus\Base\response
     */
    public function index() {
        $data = $this->repository->getAllsubscriptions();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::subscription.showallError' ) );
    }

    /**
     * Function to add new subscriptions
     *
     * @param Request $request
     * @return \Contus\Base\response
     */
    public function store(Request $request) {
        $save = $this->repository->addOrUpdatesubscriptions ();
        return ($save === 1) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'customer::subscription.added' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::subscription.addedError' ) );
    }

    /**
     * function to get one subscription information
     *
     * @param Request $request
     * @param int $subscriptionId
     * @return \Contus\Base\response
     */
    public function show(Request $request, $subscriptionId) {
        $data = $this->repository->getsubscription ( $subscriptionId );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [ 'data' => $data ], trans ( 'customer::subscription.showError' ) );
    }

    /**
     * function to update one subscription information
     *
     * @param Request $request
     * @param int $subscriptionId
     * @return \Contus\Base\response
     */
    public function update(Request $request, $subscriptionId) {
        $update = $this->repository->addOrUpdatesubscriptions ( $subscriptionId );
        return ($update === 1) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'customer::subscription.updated' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::subscription.updatedError' ) );
    }

    /**
     * function to delete one subscription information
     *
     * @param Request $request
     * @param int $subscriptionId
     * @return \Contus\Base\response
     */
    public function destroy(Request $request, $subscriptionId) {
        $data = $this->repository->deletesubscription ( $subscriptionId );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'customer::subscription.deleted' )] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::subscription.deletedError' ) );
    }
}
