<?php

/**
 * Customer Favourite Video Controller
 *
 * @name Customer FavouriteVideoController
 * @vendor Contus
 * @package Customer
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Customer\Api\Controllers\Customer;

use Contus\Base\ApiController;
use Contus\Customer\Repositories\FavouriteVideoRepository;
use Contus\Customer\Models\Customer;
use Illuminate\Http\Request;

class FavouriteVideosController extends ApiController {

    /**
     * Construct method
     */
    public function __construct(FavouriteVideoRepository $TemplatesRepository) {
        parent::__construct ();
        $this->repository = $TemplatesRepository;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data = $this->repository->getAllFavouriteVideos ();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'response' => $data,'message'=>trans('customer::favouritevideos.add.success') ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::favouritevideos.showError' ) );
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $data = $this->repository->addFavouriteVideos ();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'customer::favouritevideos.add.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::favouritevideos.add.error' ) );
    }
    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy() {
        $data = $this->repository->deleteFavouriteVideo ();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'customer::favouritevideos.delete.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::favouritevideos.delete.error' ) );
    }
}
