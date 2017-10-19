<?php

/**
 * Payment Controller
 *
 * To manage the LatestNews such as create, edit and delete the admin users
 *
 * @name Payment Controller
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Payment\Http\Controllers\Admin;

use Contus\Base\Controller as BaseController;
use Carbon\Carbon;
use Contus\Payment\Repositories\PaymentRepository;

class PaymentController extends BaseController {
    /**
     * Construct method
     */
    public function __construct(PaymentRepository $paymentRepository) {
        parent::__construct ();
        $this->_paymentRepository = $paymentRepository;
        $this->_paymentRepository->setRequestType ( static::REQUEST_TYPE );
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\View
     */
    public function getIndex() {
        return view ( 'payment::admin.payments.index', [ 'transactions' => $this->_paymentRepository->getAllPayments () ] );
    }
    
    /**
     * get Grid template
     *
     * @return \Illuminate\Http\View
     */
    public function getGrid() {
        return view ( 'payment::admin.payments.grid' );
    }
    /**
     * get Grid template
     *
     * @return \Illuminate\Http\View
     */
    public function getGridlist() {
        return view ( 'payment::admin.payments.gridView' );
    }
    
    /**
     * Logout admin login
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout() {
        $this->auth->user ()->where ( 'id', $this->auth->user ()->id )->update ( [ 'last_logged_out_at' => Carbon::now () ] );
        auth ()->logout ();
        return redirect ( '/' );
    }
}
