<?php

/**
 * Banner Controller
 * To manage the functionalities related to the Banner management
 *
 * @vendor Contus
 *
 * @package cms
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Api\Controllers\Cms;

use Contus\Base\ApiController;
use Contus\Cms\Repositories\BannerRepository;
use Contus\Base\Repositories\UploadRepository;
use Contus\Base\Helpers\StringLiterals;

class BannerController extends ApiController {
    /**
     * class property to hold the instance of BannerRepository
     *
     * @var \Contus\Base\Repositories\BannerRepository Construct method
     */
    public function __construct(BannerRepository $bannerRepository, UploadRepository $uploadRepository) {
        parent::__construct ();
        $this->repository = $bannerRepository;
        $this->uploadRepository = $uploadRepository;
    }
    
    /**
     * To get the Static content info.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo() {
        $data = $this->repository->getBanners ();
        
        unset ( $data->id );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::subscription.showError' ) );
    }
    
    /**
     * This function used to get the listof Banners
     *
     * @return \Contus\Base\response
     */
    public function getTestimonialList() {
        $data = $this->repository->getBannerlists ();
        unset ( $data->id );
        return ($data) ? $this->getSuccessJsonResponse ( [ 'message' => $data ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'customer::subscription.showError' ) );
    }
    /**
     * Store a newly created Banner
     *
     * @return \Illuminate\Http\Response
     */
    public function postAdd() {
        $isCreated = false;
        
        if ($this->repository->addOrUpdateBannerContents ()) {
            
            $isCreated = true;
            
            $this->request->session ()->flash ( StringLiterals::SUCCESS, trans ( 'cms::staticcontent.add.success' ) );
        }
        
        return ($isCreated) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::staticcontent.add.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::staticcontent.add.error' ) );
    }
    
    /**
     * Update the specified banners in storage.
     *
     * @param int $id 
     * @return \Illuminate\Http\Response
     */
    public function postEdit($staticId) { 
        $isCreated = false; 
        
        if ($this->repository->addOrUpdateBannerContents ( $staticId )) {
            $isCreated = true;
            $this->request->session ()->flash ( 'success', trans ( 'cms::staticcontent.update.success' ) );
        }
        
        return ($isCreated) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::staticcontent.update.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::staticcontent.update.error' ) );
    }
    public function postBannerImage() { 
        $tempImageInfo = $this->uploadRepository->tempUploadImage ();  
        return empty ( $tempImageInfo ) ? $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.messsage.unable_to_upload' ) ) : $tempImageInfo;
    }
}