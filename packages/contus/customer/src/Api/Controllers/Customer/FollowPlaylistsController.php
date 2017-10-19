<?php

/**
 * Customer Follow Playlists Controller
 *
 * @vendor Contus
 * @package Customer
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Customer\Api\Controllers\Customer;

use Contus\Base\ApiController;
use Contus\Customer\Repositories\FollowPlaylistsRepository;
use Contus\Customer\Models\Customer;
use Illuminate\Http\Request;

class FollowPlaylistsController extends ApiController {

    /**
     * Construct method
     */
    public function __construct(FollowPlaylistsRepository $TemplatesRepository) {
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
        $data = $this->repository->getAllFollowPlaylists ();
        if(($this->request->header ( 'x-request-type' ) == 'mobile')){
            return ($data) ? $this->getSuccessJsonResponse ( [ 'response' => $data,'message'=> trans ( 'customer::followplaylist.add.getsuccess' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::followplaylist.showError' ) );
        }else{
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::followplaylist.showError' ) );
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $data = $this->repository->addFollowPlaylists ();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'customer::followplaylist.add.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::followplaylist.add.error' ) );
    }
    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy() {
        $data = $this->repository->deleteFollowPlaylists ();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'customer::followplaylist.delete.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::followplaylist.delete.error' ) );
    }
}
