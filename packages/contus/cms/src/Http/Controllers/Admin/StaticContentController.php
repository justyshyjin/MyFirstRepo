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
namespace Contus\Cms\Http\Controllers\Admin;

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\View
     */
    public function getEditStaticContent($id) {
        return view ( 'cms::admin.static.edit', [ 'id' => $id,'rules' => $this->_staticContentRepository->getRules () ] );
    }

    /**
     * Display a Edit page.
     *
     * @return \Illuminate\Http\View
     */
    public function getIndex() {
        return view ( 'cms::admin.static.index', [ 'content' => $this->_staticContentRepository->getAllStaticContents () ] );
    }

    /**
     * get Grid template
     *
     * @return \Illuminate\Http\View
     */
    public function getGrid() {
        return view ( 'cms::admin.static.grid' );
    }
    /**
     * get Grid template
     *
     * @return \Illuminate\Http\View
     */
    public function getGridlist() {
        return view ( 'cms::admin.static.gridView' );
    }
}
