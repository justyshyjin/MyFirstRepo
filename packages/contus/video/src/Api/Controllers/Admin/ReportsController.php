<?php

/**
 * Reports Controller
 *
 * To manage the Reports of the application.
 *
 * @name       Reports Controller
 * @version    1.0
 * @author     Contus Team <developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Api\Controllers\Admin;

use Illuminate\Http\Request;
use Contus\Video\Repositories\DashboardRepository;
use Contus\Base\ApiController;
use Contus\Base\Helpers\StringLiterals;

class ReportsController extends ApiController {
    public function __construct(DashboardRepository $dashboardRepository) {
        parent::__construct ();
        $this->repository = $dashboardRepository;
        $this->repository->setRequestType ( static::REQUEST_TYPE );
    }
    
    /**
     * get Information for create form
     * return various information request by the form
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo($customerType = "", $videoType = "",$subcribedUserType = "") { 
        return $this->getSuccessJsonResponse ( ['info' => ['total_number_of_videos' => $this->repository->getTotalNumberOfVideos (),
                        'total_progressing_videos' => $this->repository->getTotalProgressingVideos (),
                        'total_video_presets' => $this->repository->getTotalVideoPresets (),'active_video_presets' => $this->repository->getActiveVideoPresets (),
                        'pdf_count' => $this->repository->getVideDocumentCount ( 'pdf' ),
                        'word_count' => $this->repository->getVideDocumentCount ( 'word' ),
                        'mp3_count' => $this->repository->getVideDocumentCount ( 'mp3' ),
                        'aws_stats' => $this->repository->getAWSStats (),'latest_videos' => $this->repository->getLatestVideos (),
                        'progressing_videos' => $this->repository->getProgressingVideos (),'top_categories' => $this->repository->getTopCategories (),'date_wise_video_upload_count' => $this->repository->getDateWiseVideoUploadCount (),
                        'current_year_month_string' => date ( 'Y' ) . '-' . date ( 'm' ),'total_aws_cost' => $this->repository->getTotalAWSCost (),'monthly_aws_cost' => $this->repository->getMonthlyAWSCost (),
                        'total_number_of_active_videos' => $this->repository->getVideDocumentCount ( 'active' ),
                        'total_number_of_inactive_videos' => $this->repository->getVideDocumentCount ( 'inactive' ),
                        'total_number_of_live_videos' => $this->repository->getVideDocumentCount ( 'live' ),
                        'total_number_of_customer' => $this->repository->getCustomersCountData ( 'all' ),'total_number_of_active_customer' => $this->repository->getCustomersCountData ( 'activecustomer' ),
                        'total_number_of_inactive_customer' => $this->repository->getCustomersCountData ( 'inactivecustomer' ),
                        'customer_data' => $this->repository->getCustomerData ( $customerType ),'video_data' => $this->repository->getVideoData ( $videoType ),'subcribed_count' => $this->repository->getSubscribedUserCount (),
                        'comment_count' => $this->repository->getTotalComment (),
                        'subscribed_user_data' => $this->repository->getSubscribedUserData($subcribedUserType)
                ]
                 
        ] );
    }
} 
 