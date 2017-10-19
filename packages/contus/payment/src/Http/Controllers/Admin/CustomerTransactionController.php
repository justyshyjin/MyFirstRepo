<?php

/**
 * TransactionController Controller
 *
 * To manage the LatestNews such as create, edit and delete the admin users
 *
 * @name TransactionController
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Payment\Http\Controllers\Admin;

use Contus\Base\Controller as BaseController;
use Carbon\Carbon;
use Contus\Payment\Repositories\TransactionRepository;

class CustomerTransactionController extends BaseController {
    /**
     * Construct method
     */
    public function __construct(TransactionRepository $transactionRepository) {
        parent::__construct ();
        $this->_transactionRepository = $transactionRepository;
        $this->_transactionRepository->setRequestType ( static::REQUEST_TYPE );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\View
     */
    public function getIndex() {
        return view ( 'payment::customer.transactionlist.index');
    }

    /**
     * get Grid template
     *
     * @return \Illuminate\Http\View
     */
    public function getGridlist() {
        return view ( 'payment::customer.transactionlist.gridView' );
    }
}
