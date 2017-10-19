<?php
namespace Contus\Video\Api\Controllers\Frontend;

use Contus\Video\Repositories\FrontVideoRepository;
use Contus\Base\ApiController;
use Contus\Video\Repositories\CategoryRepository;
use Contus\Customer\Repositories\SubscriptionRepository;
use Contus\Cms\Repositories\TestimonialRepository;
use Contus\Video\Repositories\PlaylistRepository;
use Contus\Notification\Repositories\NotificationRepository;
use Contus\Customer\Repositories\FavouriteVideoRepository;




class VideoListingController extends ApiController {
	/**
	 * constructor funtion for video controller
	 *
	 * @param FrontVideoRepository $videosRepository
	 * @param CategoryRepository $categoryRepository
	 */
	public function __construct(FrontVideoRepository $videosRepository, CategoryRepository $categoryRepository, SubscriptionRepository $subscriptionRepository) {
		parent::__construct ();
		$this->repository = $videosRepository;
		$this->category = $categoryRepository;
		$this->subscription = $subscriptionRepository;
		
	}

	/**
	 * Function to send all subscriptions
	 *
	 * @return \Contus\Base\response
	 */
	public function getAllSubscriptions() {
		$fetch ['subscription'] = $this->subscription->getAllSubscriptions ();
		if (array_filter ( $fetch )) {
			return $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ),'response' => $fetch ] );
		} else {
			return $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.fetch.error' ) );
		}
				
}
/**
	 * Function to send one subscriptions
	 *
	 * @return \Contus\Base\response
	 */
public function getOneSubscriptions($slug) {
	$fetch ['subscription'] = $this->subscription->getOneSubscriptions ($slug);
	if (array_filter ( $fetch )) {
		return $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ),'response' => $fetch ] );
	} else {
		return $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.fetch.error' ) );
	}

}
/**
	 * Function to send all videos
	 *
	 * @return \Contus\Base\response
	 */
public function getAllVideos() {
	$fetch ['categories'] = $this->category->getAllCategoriesSlugs ();
	$fetch ['tags'] = $this->repository->getallTags ();
	$fetch ['videos'] = $this->repository->getallVideo ();
	$fetch ['live_videos'] = $this->repository->getallTags ();
	if (array_filter ( $fetch )) {
		return $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ),'response' => $fetch ] );
	} else {
		return $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.fetch.error' ) );
	}
}
/**
	 * Function to get all videos
	 *
	 * @return \Contus\Base\response
	 */
public function getAllCategory() {
	$fetch ['categories'] = $this->category->getCategoriesLists();
	
	if (array_filter ( $fetch )) {
		return $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ),'response' => $fetch ] );
	} else {
		return $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.fetch.error' ) );
	}
}

/**
	 * Function to search the videos
	 *
	 * @return \Contus\Base\response
	 */
public function search() {
	
	$fetch ['videos'] = $this->repository->getallVideo (false);
	echo $fetch['videos'];exit;
	if (array_filter ( $fetch )) {
		return $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ),'response' => $fetch ] );
	} else {
		return $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.fetch.error' ) );
	}
}

/**
	 * Function to ger particular video
	 *
	 * @return \Contus\Base\response
	 */
public function getVideos($slug) {
	$fetch ['videos'] = $this->repository->getVideoSlug ( $slug );
	if ($this->request->header ( 'x-request-type' ) == 'mobile') {
		$fetch ['comments'] = $this->repository->getCommentsVideoSlug ( $slug, 3, false );
		$fetch ['related'] = $this->category->getRelatedVideoSlug ( $slug, 4, false );
		$fetch ['video_realted_playlist'] = $this->category->browseCategoryPlaylist ();
	} else {
		$fetch ['subscription'] = $this->subscription->getAllSubscriptions ();
	} 
	if (array_filter ( $fetch )) {
		return $this->getSuccessJsonResponse ( [ 'message' => trans ( 'video::videos.fetch.success' ),'response' => $fetch ] );
	} else {
		return $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.fetch.error' ) );
	}
}

}
