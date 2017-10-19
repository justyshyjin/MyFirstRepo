<?php

/**
 * Dashboard Repository
 *
 * To manage the functionalities related to videos
 * @name       DashboardRepository
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Video\Contracts\IDashboardRepository;
use Contus\Video\Models\Video;
use Contus\Video\Models\Comment;
use Contus\Video\Models\VideoPreset;
use Contus\Video\Models\Option;
use Contus\Video\Models\Category;
use Contus\Base\Helpers\StringLiterals;
use DB;
use Contus\Video\Models\AwsMonthWiseBilling;
use Contus\Customer\Models\Customer;
use Carbon\Carbon;
use Contus\Payment\Models\PaymentTransactions;
use Faker\Provider\Text;

class DashboardRepository extends BaseRepository implements IDashboardRepository {
/**
 * class property to hold the instance of Video Model
 *
 * @var \Contus\Video\Models\Video
 */
 public $video;
 /**
  * class property to hold the instance of VideoPreset Model
  *
  * @var \Contus\Video\Models\VideoPreset
  */
 public $videoPreset;
 /**
  * class property to hold the instance of Option Model
  *
  * @var \Contus\Video\Models\Option
  */
 public $option;
 /**
  * class property to hold the instance of Category Model
  *
  * @var \Contus\Video\Models\Category
  */
 public $category;
 public $customer;
 public $comment;

 /**
  * Constructor method of the class in which instances of the model files are fetched.
  *
  * @param Video $video object Instance of Video Model class.
  * @param VideoPreset $videoPreset object Instance of VideoPreset Model class.
  * @param Option $option object Instance of Option Model class.
  * @param Category $category object Instance of Category Model class.
  */
 public function __construct(Video $video, VideoPreset $videoPreset, Option $option, Category $category, Customer $customer, Comment $comment) {
  parent::__construct ();

  /**
   * Set other class objects to properties of this class.
   */
  $this->video = $video;
  $this->videoPreset = $videoPreset;
  $this->option = $option;
  $this->category = $category;
  $this->customer = $customer;
  $this->comment = $comment;
 }

 /**
  * Function to get total number of videos in the application.
  *
  * @see \Contus\Video\Contracts\IDashboardRepository::getTotalNumberOfVideos()
  * @return integer Number of videos in the application.
  */
 public function getTotalNumberOfVideos() {
     return $this->video->where(StringLiterals::IS_ARCHIVED, 0)->count();
 }
 /**
  * Function to get all video count
  *
  * @param string $type
  * @return string
  */
 public function getVideDocumentCount($type){
     $videoData = '';
     switch ($type) {
         case 'pdf':
             $videoData = $this->video->where(StringLiterals::IS_ARCHIVED, 0)->where('pdf','!=','')->count();
             break;
         case 'word':
             $videoData = $this->video->where(StringLiterals::IS_ARCHIVED, 0)->where('word','!=','')->count();
             break;
         case 'live':
             $videoData = $this->video->where(StringLiterals::IS_ARCHIVED, 0)->where('youtube_live',1)->count();
             break;
         case 'active':
                $videoActiveCount = $this->video->where(StringLiterals::IS_ARCHIVED, 0)->where('is_active',1)->count();
                $checkValue = '';
                $stringcount = strlen((string)$videoActiveCount);
                for($i=2;$i<=$stringcount;$i++)
                {$checkValue = $checkValue.'0';}
                $checkValue = '1'.$checkValue;
                $intergercount = intval(($videoActiveCount/$checkValue)*1);
                $videoData = $intergercount * $checkValue;
                break;
          case 'inactive':
              $videoData = $this->video->where(StringLiterals::IS_ARCHIVED, 0)->where('is_active',0)->count();
                 break;
          case 'all':
              $videoData = $this->video->where(StringLiterals::IS_ARCHIVED, 0)->count();
                 break;
          case  'audio':
              $videoData = $this->video->where(StringLiterals::IS_ARCHIVED, 0)->where('mp3','!=','')->count();
              break;
         default:
             $videoData = $this->video->where(StringLiterals::IS_ARCHIVED, 0)->where('mp3','!=','')->count();
     }
     return $videoData;
 }
 /**
  * Function to get customers count
  *
  * @param string $types
  * @return string
  */
 public function getCustomersCountData($types){
     $customerData = '';
     switch ($types) {
         case 'activecustomer':
             $customerData = $this->customer->where('is_active',1)->count();
             break;
             case 'inactivecustomer':
                 $customerData = $this->customer->where('is_active',0)->count();
                 break;
             default:
                 $customerData = $this->customer->count();
             }
       return $customerData;
 }
 /**
  * Function to get total number of comments
  *
  * @return object
  */
 public function getTotalComment(){
     return $this->comment->count();
 }
 /**
  * Function to get customer data
  *
  * @param string $type
  */
 public function getCustomerData($type = ""){
     $customer = array();
     if($type == 'day'){
         for($i = 0; $i < 12; $i ++){
             $forToday = Carbon::now();
             $beforeDate = $forToday->subDays($i);
             $beforeDate = $beforeDate->format('d');
             $customer[$i]['count'] = Customer::whereRaw('DAY(created_at) = '.$forToday->format('d').' and MONTH(created_at) ='.$forToday->format('m'))->get()->count();
             $customer[$i]['month'] = $forToday->format('Y,m,d');
         }
     }
     else if($type == 'year'){
         for($i = 0; $i < 12; $i ++){
             $forToday = Carbon::now();
             $beforeDate = $forToday->subYears($i);
             $beforeDate = $beforeDate->format('y');
             $customer[$i]['count'] = Customer::whereRaw('YEAR(created_at) ='.$forToday->format('Y'))->get()->count();
             $customer[$i]['month'] = $forToday->format('Y');
         }
     }
     else{
         for($i = 0; $i < 12; $i ++){
             $forToday = Carbon::now();
             $beforeDate = $forToday->subMonths($i);
             $beforeDate = $beforeDate->format('');
             $customer[$i]['count'] = Customer::whereRaw('MONTH(created_at) = '.$forToday->format('m').' and YEAR(created_at) ='.$forToday->format('Y'))->get()->count();
             $customer[$i]['month'] = $forToday->format('Y,m');
         }
     }
   return $customer;
 }
 /**
  * Function to get video datas based on month and year
  *
  * @param string $type
  */
 public function getVideoData($type = ""){
     $video = array();
     if($type == 'day'){
         for($i = 0; $i < 12; $i ++){
             $forToday = Carbon::now();
             $beforeDate = $forToday->subDays($i);
             $beforeDate = $beforeDate->format('d');
             $video[$i]['count'] = Video::whereRaw('DAY(created_at) = '.$forToday->format('d').' and MONTH(created_at) ='.$forToday->format('m'))->get()->count();
             $video[$i]['month'] = $forToday->format('Y,m,d');
         }
     }
     else if($type == 'year'){
         for($i = 0; $i < 12; $i ++){
             $forToday = Carbon::now();
             $beforeDate = $forToday->subYears($i);
             $beforeDate = $beforeDate->format('y');
             $video[$i]['count'] = Video::whereRaw('YEAR(created_at) ='.$forToday->format('Y'))->get()->count();
             $video[$i]['month'] = $forToday->format('Y');
         }
     }
     else{
         for($i = 0; $i < 12; $i ++){
             $forToday = Carbon::now();
             $beforeDate = $forToday->subMonths($i);
             $beforeDate = $beforeDate->format('m');
             $video[$i]['count'] = Video::whereRaw('MONTH(created_at) = '.$forToday->format('m').' and YEAR(created_at) ='.$forToday->format('Y'))->get()->count();
             $video[$i]['month'] = $forToday->format('Y,m');
         }
     }
     return $video;
 }
 /**
  * Function to get subscribed user data based on month and year
  *
  * @param string $select
  */
 public function getSubscribedUserData($select = ""){
     $subscribed = array();
     if($select == 'day'){
         for($i = 0; $i < 12; $i ++){
             $forToday = Carbon::now();
             $beforeDate = $forToday->subDays($i);
             $beforeDate = $beforeDate->format('d');
             $subscribed[$i]['count'] = Customer::whereRaw('DAY(created_at) = '.$forToday->format('d').' and MONTH(created_at) ='.$forToday->format('m'))->whereNotNull('expires_at')->get()->count();
             $subscribed[$i]['month'] = $forToday->format('Y,m,d');
         }
     }
     else if($select == 'year'){
         for($i = 0; $i < 12; $i ++){
             $forToday = Carbon::now();
             $beforeDate = $forToday->subYears($i);
             $beforeDate = $beforeDate->format('y');
             $subscribed[$i]['count'] = Customer::whereRaw('YEAR(created_at) ='.$forToday->format('Y'))->whereNotNull('expires_at')->get()->count();
             $subscribed[$i]['month'] = $forToday->format('Y');
         }
     }
     else{
         for($i = 0; $i < 12; $i ++){
             $forToday = Carbon::now();
             $beforeDate = $forToday->subMonths($i);
             $beforeDate = $beforeDate->format('m');
             $subscribed[$i]['count'] = Customer::whereRaw('MONTH(created_at) = '.$forToday->format('m').' and YEAR(created_at) ='.$forToday->format('Y'))->whereNotNull('expires_at')->get()->count();
             $subscribed[$i]['month'] = $forToday->format('Y,m');
         }
     }
     return $subscribed;
 }
 /**
  * Function to get subscribed user count
  *
  * @return int
  */
 public function getSubscribedUserCount(){
     return $this->customer->whereNotNull('expires_at')->count();
 }
 /**
  * Function to get total number of videos that are being progressed(transcoded).
  *
  * @see \Contus\Video\Contracts\IDashboardRepository::getTotalProgressingVideos()
  * @return integer Total number of progressing videos.
  */
 public function getTotalProgressingVideos() {
     return $this->video->where(StringLiterals::IS_ARCHIVED, 0)->where(function($query) {
         $query->where(StringLiterals::JOBSTATUS, 'Video Uploaded')->OrWhere(StringLiterals::JOBSTATUS, 'Progressing');
     })->count();
 }

 /**
  * Function to get total number of presets available for transcoding.
  *
  * @see \Contus\Video\Contracts\IDashboardRepository::getTotalVideoPresets()
  * @return integer Total number of video presets.
  */
 public function getTotalVideoPresets() {
     return $this->videoPreset->count();
 }

 /**
  * Function to get number of active video presets.
  *
  * @see \Contus\Video\Contracts\IDashboardRepository::getActiveVideoPresets()
  * @return integer Total number of active presets.
  */
 public function getActiveVideoPresets() {
     return $this->videoPreset->where('is_active', 1)->count();
 }

 /**
  * Function to get statistics from AWS.
  *
  * @see \Contus\Video\Contracts\IDashboardRepository::getAWSStats()
  * @return array The statistics about AWS S3 bucket.
  */
 public function getAWSStats() {
     return $this->option->where('option_group', 'aws_stats')->get()->toArray();
 }

 /**
  * Function to get latest videos from the database.
  *
  * @see \Contus\Video\Contracts\IDashboardRepository::getLatestVideos()
  * @return array Latest videos uploaded in the application.
  */
 public function getLatestVideos() {
     return $this->video->with('videocategory.category', 'transcodedvideos.presets')->where (StringLiterals::IS_ARCHIVED, 0)->where(StringLiterals::JOBSTATUS, 'Complete')->orderBy('id', 'desc')->take(5)->get()->toArray();
 }

 /**
  * Function to get progressing videos from the database.
  *
  * @see \Contus\Video\Contracts\IDashboardRepository::getProgressingVideos()
  * @return array The videos which are being progressed(transcoded).
  */
 public function getProgressingVideos() {
     return $this->video->with(['videocategory.category', 'transcodedvideos.presets'])->where (StringLiterals::IS_ARCHIVED, 0)->where(function($query) {
         $query->where(StringLiterals::JOBSTATUS, 'Video Uploaded')->OrWhere(StringLiterals::JOBSTATUS, 'Progressing');
     })->orderBy('id', 'desc')->take(5)->get()->toArray();
 }

 /**
  * Function to get top categories(categories with most number of videos) from the database.
  *
  * @see \Contus\Video\Contracts\IDashboardRepository::getTopCategories()
  * @return array Top categories fetched from the database.
  */
 public function getTopCategories() {
     return $this->category->leftJoin('video_categories', 'categories.id', '=', 'video_categories.category_id')
     ->leftJoin('videos', 'video_categories.video_id', '=', 'videos.id')
     ->leftJoin('categories AS c2', 'categories.parent_id', '=', 'c2.id')
     ->select(DB::raw('categories.*, COUNT(videos.id) as videos_count, c2.title as parent_category'))
     ->where('categories.is_deletable', 1)
     ->where('videos.is_archived', 0)
     ->groupBy('categories.title')
     ->orderBy('videos_count', 'desc')->take(5)->get()->toArray();
 }

 /**
  * Function to get date wise video upload count which will be used to generate the chart in the dashboard.
  *
  * @see \Contus\Video\Contracts\IDashboardRepository::getDateWiseVideoUploadCount()
  * @return array Date wise video upload count.
  */
 public function getDateWiseVideoUploadCount() {
     return $this->video->select(DB::raw('DATE(created_at) as date, DAY(created_at) as day_of_month, COUNT(id) as no_of_videos'))
     ->whereRaw('MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())')
     ->groupBy('day_of_month')
     ->orderBy('day_of_month', 'asc')->get()->toArray();
 }
 /**
  * Function to get total cost of AWS from the database.
  *
  * @return float Total AWS cost.
  */
 public function getTotalAWSCost() {
     return AwsMonthWiseBilling::sum('total_cost');
 }
 /**
  * Function to get Revenue From Subscription
  */
 public function getRevenue(){
     return PaymentTransactions::where('payment_transactions.status','Success')->whereRaw("payment_transactions.created_at >= '2017-04-10 00:00:00'")->join ( 'subscription_plans', function ($join) {
                    $join->on ( 'subscription_plans.id', '=', 'payment_transactions.subscription_plan_id' );
                } )->sum('amount');
 }
 /**
  * Function to get month wise AWS cost for last two months.
  *
  * @return array Month wise AWS cost for last two months.
  */
 public function getMonthlyAWSCost() {
     return AwsMonthWiseBilling::orderBy('id', 'desc')->take(2)->get()->toArray();
 }
}