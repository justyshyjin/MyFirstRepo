<?php

/**
 * Testimonial Controller
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
use Contus\Cms\Repositories\TestimonialRepository;

class TestimonialController extends BaseController {
    /**
     * Construct method
     */
    public function __construct(TestimonialRepository $TestimonialRepository) {
        parent::__construct ();
        $this->_testimonialRepository = $TestimonialRepository;
        $this->_testimonialRepository->setRequestType ( static::REQUEST_TYPE );
    }
    
    /**
     * Display a listing of the testimonials.
     *
     * @return \Illuminate\Http\View
     */
    public function getIndex() {
        return view ( 'cms::admin.testimonial.index');
    }
    
    /**
     * get Grid template
     *
     * @return \Illuminate\Http\View
     */
    public function getGrid() {
        return view ( 'cms::admin.testimonial.grid' );
    }
    /**
     * get Grid List
     *
     * @return \Illuminate\Http\View
     */
    public function getGridlist() {
        return view ( 'cms::admin.testimonial.gridView' );
    }
    
}
