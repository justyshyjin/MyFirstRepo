<?php

/**
 * AdminUser Controller
 *
 * To manage the LatestNews such as create, edit and delete the admin users
 *
 * @name LatestNews Controller
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Http\Controllers\Admin;

use Contus\Cms\Repositories\EmailTemplatesRepository;
use Contus\Base\Controller as BaseController;
use Carbon\Carbon;

class EmailController extends BaseController {
    /**
     * Construct method
     */
    public function __construct(EmailTemplatesRepository $emailRepository) {
        parent::__construct ();
        $this->_emailRepository = $emailRepository;
        $this->_emailRepository->setRequestType ( static::REQUEST_TYPE );
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\View
     */
    public function getIndex() {
        return view ( 'cms::admin.email.index', [ 'email' => $this->_emailRepository->getAllEmailTemplates () ] );
    }
    
    /**
     * get Grid template
     *
     * @return \Illuminate\Http\View
     */
    public function getGrid() {
        return view ( 'cms::admin.email.grid' );
    }
    /**
     * get Grid template
     *
     * @return \Illuminate\Http\View
     */
    public function getGridlist() {
        return view ( 'cms::admin.email.gridView' );
    }
    /**
     * Edit the email template file
     * @param unknown $id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function getDetailsEmailEdit($id) {
        return view ( 'cms::admin.email.edit' ,['id' => $id,'rules' => $this->_emailRepository->getRules ()]);
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
