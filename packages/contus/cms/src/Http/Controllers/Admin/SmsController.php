<?php

/**
 * Sms Controller.
 * To manage the sms template such as create, edit and delete
 * 
 * @name Sms Controller
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Http\Controllers\Admin;

use Contus\Base\Controller as BaseController;
use Carbon\Carbon;
use Contus\Cms\Repositories\SmsTemplatesRepository;

class SmsController extends BaseController {
    /**
     * Construct method
     */
    public function __construct(SmsTemplatesRepository $smsTemplateRepository) {
        parent::__construct ();
        $this->_smsTemplateRepository = $smsTemplateRepository;
        $this->_smsTemplateRepository->setRequestType ( static::REQUEST_TYPE );
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\View
     */
    public function getIndex() {
        return view ( 'cms::admin.sms.index', [ 'sms' => $this->_smsTemplateRepository->getAllSmsTemplates () ] );
    }
    
    /**
     * get Grid template
     *
     * @return \Illuminate\Http\View
     */
    public function getGrid() {
        return view ( 'cms::admin.sms.grid' );
    }
    /**
     * get Grid template
     *
     * @return \Illuminate\Http\View
     */
    public function getGridlist() {
        return view ( 'cms::admin.sms.gridView' );
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
