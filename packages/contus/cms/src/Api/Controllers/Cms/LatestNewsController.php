<?php

/**
 * Latest News Controller
 * To manage the functionalities related to the Latest News gird api methods
 *
 * @name LatestNews Controller
 * @vendor Contus
 * @package cms
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Api\Controllers\Cms;

use Contus\Base\ApiController;
use Contus\Cms\Repositories\LatestNewsRepository;
use Contus\Base\Repositories\UploadRepository;
use Contus\Base\Helpers\StringLiterals;
use Illuminate\Http\Request;

class LatestNewsController extends ApiController {
    /**
     * class property to hold the instance of LatestNewsRepository
     *
     * @var \Contus\Base\Repositories\LatestNewsRepository
     */
    public $latestNewsRepository;
    public $uploadRepository;
    /**
     * Construct method
     */
    public function __construct(LatestNewsRepository $latestNewsRepository, UploadRepository $uploadRepository) {
        parent::__construct ();
        $this->repository = $latestNewsRepository;
        $this->uploadRepository = $uploadRepository;
    }
    
    /**
     * To get the latestnews info.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo() {
        return $this->getSuccessJsonResponse ( [ 'info' => [ 'rules' => $this->repository->getRules (),'allLatestNews' => $this->repository->getAllLatestNews () ] ] );
    }
    /**
     * Get the blog informations
     * 
     * @return \Contus\Base\response
     */
    public function getData() {
        return $this->getSuccessJsonResponse ( [ 'response' => $this->repository->getAllLatestNews () ] );
    }
    /**
     * get the blog details those who are all posted the blog and related videos like that
     * 
     * @param unknown $slug 
     * @return \Contus\Base\response
     */
    public function getBlogDetail($slug) {
        return $this->getSuccessJsonResponse ( [ 'response' => $this->repository->getLatestNewsSlug ( $slug ) ] );
    }
    /**
     * Customer can able to post the latest news of the blog
     * 
     * @return \Contus\Base\response
     */
    public function postLatestnewsImage() {
        $tempImageInfo = $this->uploadRepository->tempUploadImage ();
        return empty ( $tempImageInfo ) ? $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.messsage.unable_to_upload' ) ) :  $tempImageInfo;
    }
    /**
     * Store a newly created latest news.
     *
     * @return \Illuminate\Http\Response
     */
    public function postAdd() {
        $isCreated = false;
        
        if ($this->repository->addOrUpdateLatestNews ()) {
            $isCreated = true;
            $this->request->session ()->flash ( StringLiterals::SUCCESS, trans ( 'cms::latestnews.add.success' ) );
        }
        
        return ($isCreated) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::latestnews.add.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::latestnews.add.error' ) );
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param int $id 
     *
     * @return \Illuminate\Http\Response
     */
    public function postEdit($newsId) {
        $isCreated = false;
        
        if ($this->repository->addOrUpdateLatestNews ( $newsId )) {
            $isCreated = true;
            $this->request->session ()->flash ( 'success', trans ( 'cms::latestnews.update.success' ) );
        }
        
        return ($isCreated) ? $this->getSuccessJsonResponse ( [ 'message' => trans ( 'cms::latestnews.update.success' ) ] ) : $this->getErrorJsonResponse ( [ ], trans ( 'cms::latestnews.update.error' ) );
    }
    
}
