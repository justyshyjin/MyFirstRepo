<?php

/**
 * Contactus Controller
 * To manage the Contact such as delete,view.
 *
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 *
 */
namespace Contus\Cms\Http\Controllers\Admin;

use Contus\Base\Controller as BaseController;
use Carbon\Carbon;
use Contus\Cms\Repositories\ContactusRepository;

class ContactusController extends BaseController {
    /**
     * Construct method
     */
    public function __construct(ContactusRepository $ContactusRepository) {
        parent::__construct ();
        $this->_contactusRepository = $ContactusRepository;
        $this->_contactusRepository->setRequestType ( static::REQUEST_TYPE );
    }
    
    /**
     * Display a listing of the The Banner management.
     * It will be like image or video.
     *
     * @return \Illuminate\Http\View
     */
    public function getIndex() {
        return view ( 'cms::admin.contactus.index' );
    }
    
    /**
     * get Grid template
     *
     * @return \Illuminate\Http\View
     */
    public function getGrid() {
        return view ( 'cms::admin.contactus.grid' );
    }
    /**
     * get Grid List
     *
     * @return \Illuminate\Http\View
     */
    public function getGridlist() {
        return view ( 'cms::admin.contactus.gridView' );
    }

    /**
     * Edit the Contact template file
     * @param unknown $id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function getDetailsContactView($id) {
        return view ( 'cms::admin.contactus.edit' ,['id' => $id,'rules' => $this->_contactusRepository->getRules ()]);
    }
    
}