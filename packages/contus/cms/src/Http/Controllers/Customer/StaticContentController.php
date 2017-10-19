<?php

/**
 * StaticContent Controller
 * To manage the static content such as create, edit and delete
 *
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Http\Controllers\Customer;

use Contus\Base\Controller as BaseController;
use Carbon\Carbon;
use Contus\Cms\Repositories\StaticContentRepository;

class StaticContentController extends BaseController {
    /**
     * Construct method
     */
    public function __construct(StaticContentRepository $StaticContentRepository) {

        parent::__construct ();
        $this->_staticContentRepository = $StaticContentRepository;
        $this->_staticContentRepository->setRequestType ( static::REQUEST_TYPE );
    }

    /**
     * Display a listing of the All the static content.
     *
     * @return \Illuminate\Http\View
     */
    public function getIndex() {
        return view ( 'cms::admin.static.index', [ 'content' => $this->_staticContentRepository->getAllStaticContents () ] );
    }

    /**
     * Method to return staticContentTemplate index blade file
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function getStaticcontent() {
      return view ( 'cms::customer.static.staticContentTemplate' );
    }
    /**
     * Function to get all static content using slug
     *
     * @param unknown $slug
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function getStaticFullContent($slug){
        $data = $this->_staticContentRepository->getStaticcontentSlug ($slug);
        return view ( 'cms::customer.static.staticTemplate',['data'=>$data] );
    }

}
