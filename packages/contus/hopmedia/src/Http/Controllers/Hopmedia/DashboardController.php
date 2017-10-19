<?php

/**
 * Hopmedia Controller
 * To manage the Hopmedia such as create, edit and delete
 *
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2017 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Hopmedia\Http\Controllers\Hopmedia;

use Contus\Base\Controller as BaseController;
use Carbon\Carbon;

class DashboardController extends BaseController {
    /**
     * Construct method
     */
    public function __construct() {

        parent::__construct ();
        
    }

    /**
     * Display a listing of the All the Index Blade.
     *
     * @return \Illuminate\Http\View
     */
    public function Index() {
       return view('hopmedia::dashboard.index');
    }

    /**
     * Mehtod to list dashboard page blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function dashboard(){
        return view ('hopmedia::dashboard.dashboard');
    }


    public function PrivacyPolicy(){
        return view('hopmedia::static.privacy_policy');
    }
   
    public function TermsCondition(){
        return view('hopmedia::static.terms_condition');
    }

    public function ContactUs(){
        return view('hopmedia::static.contactus');
    }

    public function AboutUs(){
        return view('hopmedia::static.aboutus');
    }

    public function Pricing(){
        return view('hopmedia::dashboard.pricing');
    }

    public function Features(){
        return view('hopmedia::static.feature');
    }
}
