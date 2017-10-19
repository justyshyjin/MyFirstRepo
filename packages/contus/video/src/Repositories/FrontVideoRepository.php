<?php

/**
 * Front Video Repository
 *
 * To manage the functionalities related to videos for the frontend
 *
 * @name FrontVideoRepository
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 *
 */

namespace Contus\Video\Repositories;

use Illuminate\Support\Facades\DB;
use Contus\Video\Models\Video;
use Contus\Notification\Models\Notification;
use Carbon\Carbon;
use Contus\Customer\Models\Customer;

class FrontVideoRepository extends VideoRepository
{
    /**
     * Function to get all video to frontend with filters and search
     *
     * @vendor Contus
     *
     * @package video
     * @return array
     */
    public function getallVideo($searchwidget = true)
    {
        $this->video = $this->video->whereCustomer()->where('youtube_live', '!=', 1)->has('categories')->orderBy('video_order', 'desc');
        if ($this->request->has('search') && $this->request->search != null) {
            $this->video = $this->video->where('title', 'like', '%' . $this->request->search . '%');
        }
        if ($this->request->has('category') && $this->request->category != null) {
            $categoryId = $this->category->whereIn($this->getKeySlugorId(), explode(',', $this->request->category))->pluck('id');
            $this->video = $this->video->orderBy('video_order', 'desc')->whereHas('videocategory', function ($q) use ($categoryId) {
                $q->whereIn('category_id', $categoryId);
            });
        } else {
            $categoryId = $this->category->whereIn($this->getKeySlugorId(), array_keys($this->categoryRepository->getAllCategories($this->request->main_category)))->pluck('id');
            $this->video = $this->video->orderBy('video_order', 'desc')->whereHas('videocategory', function ($q) use ($categoryId) {
                $q->whereIn('category_id', $categoryId);
            });
        }
        if ($this->request->has('tag') && $this->request->tag != null) {
            $this->video = $this->video->whereHas('tags', function ($q) {
                $q->whereIn('tag_id', explode(',', $this->request->tag));
            });
        }
        if ($searchwidget) {
            if ($this->request->header('x-request-type') == 'mobile') {
                $video = $this->video->leftJoin('favourite_videos as f1', function ($j) {
                    $j->on('videos.id', '=', 'f1.video_id')->on('f1.customer_id', '=', DB::raw((auth()->user()) ? auth()->user()->id : 0));
                })->selectRaw('videos.*,count(f1.video_id) as is_favourite')->groupBy('videos.id');
                if ($this->request->has('video_id')) {
                    $video = $video->where('videos.id', '!=', $this->request->video_id);
                }
                $video = $video->paginate(9)->toArray();
            } else {
                $video = $this->video->leftJoin('favourite_videos as f1', function ($j) {
                    $j->on('videos.id', '=', 'f1.video_id')->on('f1.customer_id', '=', DB::raw((auth()->user()) ? auth()->user()->id : 0));
                })->selectRaw('videos.*,count(f1.video_id) as is_favourite')->groupBy('videos.id')->with(['tags', 'videocategory.category'])->paginate(10)->toArray();
            }
        } else {
            $video = $this->video->select('title', $this->getKeySlugorId())->take(10)->get();
        }
        return $video;
    }

    /**
     * function to get all tags
     *
     * @vendor Contus
     *
     * @package video
     * @return unknown
     */
    public function getallTags()
    {
        if ($this->request->has('category')) {
            $categoryId = $this->category->whereIn($this->getKeySlugorId(), explode(',', $this->request->category))->pluck('id');
        } else {
            $categoryId = $this->category->whereIn($this->getKeySlugorId(), array_keys($this->categoryRepository->getAllCategories($this->request->main_category)))->pluck('id');
        }
        return $this->tag->whereHas('videos.categories', function ($query) use ($categoryId) {
            $query->whereIn('categories.id', $categoryId);
        })->pluck('name', 'id');
    }

    /**
     * Get Live Video Notification lists
     */
    public function getLiveVideoNotification()
    {
        $savedVideos = Video::where('is_archived', 0)->where('is_active', 1)->where('job_status', 'Complete')->where('notification_status', 0)->where('youtube_live', 0)->orderBy('video_order', 'desc')->get();
        $liveVideos = Video::where('is_archived', 0)->where('is_active', 1)->where('liveStatus', 'ready')->where('job_status', 'Complete')->whereRaw('DATE(scheduledStartTime) = "' . Carbon::now()->tomorrow()->toDateString() . '"')->where('notification_status', 0)->where('youtube_live', 1)->orderBy('scheduledStartTime', 'asc')->get();
        if ($liveVideos->toArray() || $savedVideos->toArray()) {
            $customer = Customer::where('email', '!=', '')->where('is_active', 1)->where('notify_newsletter', 0)->get();
            $vCount = $savedVideos->count();
            $lCount = $liveVideos->count();
            $vHtml = '';
            if ($savedVideos->toArray()) {
                for ($i = 0; $i < 5; $i++) {
                    if (!isset($savedVideos [$i])) {
                        continue;
                    }
                    $vHtml .= '<tr><td><a target="_blank" href="' . env('LS_TYPE_FRONT') . '/video-detail/' . $savedVideos [$i]->slug . '">' . $savedVideos [$i]->title . '</a></td></tr>';
                }
                $vHtml = '<p>Check out the latest ' . $vCount . ' videos added at ' . config()->get('settings.general-settings.site-settings.site_name') . '</p>
                <table>' . $vHtml . '</table><p><a target="_blank" href="' . env('LS_TYPE_FRONT') . '">View more videos from our site</a><p>';
                Video::where('is_archived', 0)->where('is_active', 1)->where('job_status', 'Complete')->where('notification_status', 0)->where('youtube_live', 0)->update(['notification_status' => 1]);
            }
            $LHtml = '';
            if ($liveVideos->toArray()) {
                for ($i = 0; $i < 5; $i++) {
                    if (!isset($liveVideos [$i])) {
                        continue;
                    }
                    $LHtml .= '<tr><td><a target="_blank" href="' . env('LS_TYPE_FRONT') . '/video-detail/' . $liveVideos [$i]->slug . '">' . $liveVideos [$i]->title . '</a></td></tr>';
                }
                $LHtml = '<p>' . config()->get('settings.general-settings.site-settings.site_name') . ' has scheduled ' . $lCount . ' videos for tomorrow.</p>
                <table>' . $LHtml . '</table><p><a target="_blank" href="' . env('LS_TYPE_FRONT') . '">View all live videos from our site</a><p>';
                $LHtml = '<h2>Live videos scheduled for tomorrow.&nbsp;</h2><p>' . $LHtml . '</p>';
                Video::where('is_archived', 0)->where('is_active', 1)->where('liveStatus', 'ready')->where('job_status', 'Complete')->whereRaw('DATE(scheduledStartTime) = "' . Carbon::now()->tomorrow()->toDateString() . '"')->where('notification_status', 0)->where('youtube_live', 1)->orderBy('scheduledStartTime', 'asc')->update(['notification_status' => 1]);
            }
            $html = '<h2>##NAME##, </h2>' . $vHtml . $LHtml;
            foreach ($customer as $c) {
                $content = str_replace(['##NAME##'], [$c->name], $html);
                $this->email($c, 'New videos in ' . config()->get('settings.general-settings.site-settings.site_name'), $content);
            }
            return true;
        }
        return false;
    }

    /**
     * function to get video with complete information using slug
     *
     * @return unknown
     */
    public function getVideoSlug($slug)
    {
        $this->video = new Video ();
        $this->video = $this->video->whereCustomer()->where('videos.' . $this->getKeySlugorId(), $slug);
        if (is_null($this->video->first())) {
            $this->throwJsonResponse(false, 404, 'This video does not exist.');
        }
        $this->video = $this->video->leftJoin('favourite_videos as f1', function ($j) {
            $j->on('videos.id', '=', 'f1.video_id')->on('f1.customer_id', '=', DB::raw((auth()->user()) ? auth()->user()->id : 0));
        })->selectRaw('videos.*,count(f1.video_id) as is_favourite')->groupBy('videos.id')->with(['categories.parent_category.parent_category']);
        $this->video = ($this->request->header('x-request-type') == 'mobile') ? $this->video->first() : $this->video->with('tags')->first();
        if ($this->request->header('x-request-type') == 'mobile') {
            if ($this->video) {
                $this->video ['comments_count'] = $this->video->comments()->where('is_active', 1)->get()->count();
                $this->video ['qa_count'] = $this->video->questions()->where('is_active', 1)->get()->count();
            } else {
                throw $this->throwJsonResponse();
            }
        }
        return $this->video;
    }

    /**
     * function to get comments for video using slug
     *
     * @return unknown
     */
    public function getCommentsVideoSlug($slug, $getCount = 10, $paginate = true)
    {
        $video = new Video ();
        $video = $video->whereCustomer()->where($this->getKeySlugorId(), $slug)->first();
        if ($video->comments()) {
            $video = $video->comments()->with(['ReplyComment.admin', 'ReplyComment.customer', 'admin', 'customer'])->orderBy('id', 'desc');
            if (config()->get('auth.providers.users.table') === 'customers') {
                $video = $video->where('is_active', 1);
            }
            if ($paginate) {
                $video = $video->paginate($getCount)->toArray();
            } else {
                $video = $video->take($getCount)->get();
            }
            return $video;
        }
        return [];
    }
    public function getVideoLikeSlug($slug){
        $videoModel = new Video ();
        $videoModel = $videoModel->whereCustomer ()->where ( $this->getKeySlugorId (), $slug )->first ();
        if ($videoModel->likes ()) {
            return $videoModel->likes ()->get();
        }
        return [ ];
    }
    public function getLikesCount($slug){
        $videoModels = new Video ();
        $videoModels = $videoModels->whereCustomer ()->where ( $this->getKeySlugorId (), $slug )->first ();
        if ($videoModels->likes ()) {
            return $videoModels->likescount();
        }
        return [ ];
    }
    public function getDislikesCount($slug){
        $videos = new Video ();
        $videos = $videos->whereCustomer ()->where ( $this->getKeySlugorId (), $slug )->first ();
        if ($videos->likes ()) {
            return $videos->dislikescount();
        }
        return [ ];
    }
    public function getLikeStatus($slug){
        $videos = new Video ();
        $videos = $videos->whereCustomer ()->where ( $this->getKeySlugorId (), $slug )->first ();
        return $this->videolikeRepository->getCountByCustomer($videos->id);
    }
    public function getWatchLaterStatus($slug){
        $videos = new Video ();
        $videos = $videos->whereCustomer ()->where ( $this->getKeySlugorId (), $slug )->first ();
        return count($this->watchlaterRepository->getCountByCustomer($videos->id));
    }

    /**
     * function to get Questions for video using slug
     *
     * @return unknown
     */
    public function getQuestionsVideoSlug($slug, $getCount = 10, $paginate = true)
    {
        $videos = new Video ();
        $videos = $videos->whereCustomer()->where($this->getKeySlugorId(), $slug)->first();
        if ($videos->questions()) {
            $videos = $videos->questions()->with(['ReplyAnswer.admin', 'ReplyAnswer.customer', 'admin', 'customer'])->orderBy('id', 'desc');
            if (config()->get('auth.providers.users.table') === 'customers') {
                $videos = $videos->where('is_active', 1);
            }
            if ($paginate) {
                $videos = $videos->paginate($getCount)->toArray();
            } else {
                $videos = $videos->take($getCount)->get();
            }
            return $videos;
        }
        return [];
    }

    /**
     * function to get live related videos
     *
     * @return object
     */
    public function getLiverelatedVideos($slug)
    {
        return $this->video->whereliveVideo()->where('id', '!=', $slug)->orderBy('scheduledStartTime', 'desc')->take(3)->get();
    }

    /**
     * function to get scheduled as well as upcomming live video lists
     *
     * @return array
     */
    public function getAllLiveVideos()
    {
        $videos = $this->video->whereallliveVideo()->orderBy('scheduledStartTime', 'ASC')->get()->toArray();
        return ['data' => $videos, 'next_page_url' => null, 'total' => count($videos)];
    }

    /**
     * function to get recorded live videos
     *
     * @return object
     */
    public function getrecordedLiveVideos($record = '', $getCount = 9, $paginate = true)
    {
        if ($record) {
            $videos = $this->video->whereRecordedliveVideo()->orderBy('id', 'desc')->get();
        } else {
            if ($this->request->header('x-request-type') == 'mobile') {
                $videos = $this->video->whereRecordedliveVideo()->orderBy('id', 'desc')->take(5)->get();
            } else {
                $videos = $this->video->whereRecordedliveVideo()->orderBy('id', 'desc');
                if ($paginate) {
                    $videos = $videos->paginate($getCount)->toArray();
                } else {
                    $videos = $videos->take($getCount)->get();
                }
            }
        }
        return $videos;
    }

    /**
     * Update live stream details
     *
     * @return object
     */
    public function getLiveTime()
    {
        return Video::where('is_active', '1')->where('liveStatus', '!=', 'complete')->where('scheduledStartTime', '!=', '')->where('is_archived', 0)->where('youtube_live', 1)->select('scheduledStartTime')->first()->orderBy('scheduledStartTime', 'desc');
    }

    /**
     * function to get live videos for widget display
     *
     * @return object
     */
    public function getOnlyLiveVideos($record = '')
    {
        $videos = new Video ();
        $serverTime = new \DateTime (date("Y-m-d H:i:s", time()));
        $videos = $videos->whereliveVideo()->orderBy('scheduledStartTime', 'asc') ;
        if ($record) {
            $liverecord = $videos->take($record)->get()->makeHidden('liveStatus')->toArray();
            foreach ($liverecord as $key => $value) {
                $checklivetime = new \DateTime ($value ['scheduledStartTime']);
                $liverecord [$key] ['liveVideoTime'] = ($checklivetime <= $serverTime);
            }
        } else {
            $liverecord = $videos->take(4)->get()->makeHidden('liveStatus');
        }
        return $liverecord;
    }

    /**
     * function to get recent videos for video using slug
     *
     * @return array
     */
    public function getVideoByType($type)
    {
        $video = $this->video->whereCustomer();
        if ($type == 'banner') {
            $video = $video->leftJoin('favourite_videos as f1', function ($j) {
                $j->on('videos.id', '=', 'f1.video_id')->on('f1.customer_id', '=', DB::raw((auth()->user()) ? auth()->user()->id : 0));
            })->selectRaw('videos.*,count(f1.video_id) as is_favourite')->groupBy('videos.id')->with(['categories.parent_category.parent_category'])->where('youtube_live', '==', 0)->orderBy('id', 'desc')->take(5)->get();
        } else if ($type == 'recent') {
            $video = $this->video->where('is_active', '1')->where('job_status', 'Complete')->where('is_archived', 0)->leftJoin('recently_viewed_videos as f1', function ($j) {
                $j->on('videos.id', '=', 'f1.video_id');
            })->where('f1.customer_id', '=', DB::raw((auth()->user()) ? auth()->user()->id : 0))->selectRaw('videos.*')->groupBy('videos.id')->with(['categories.parent_category.parent_category'])->where('youtube_live', '==', 0)->orderBy('id', 'desc')->take(4)->get();
            foreach ($video as $k => $v) {
                $video [$k] ['is_favourite'] = $v->authfavourites()->get()->count();
            }
            if (!count($video) > 0) {
                $video = $this->video->where('is_active', '1')->where('job_status', 'Complete')->where('is_archived', 0)->where('trailer_status', 1)->leftJoin('favourite_videos as f1', function ($j) {
                    $j->on('videos.id', '=', 'f1.video_id')->on('f1.customer_id', '=', DB::raw((auth()->user()) ? auth()->user()->id : 0));
                })->selectRaw('videos.*,count(f1.video_id) as is_favourite')->groupBy('videos.id')->with(['categories.parent_category.parent_category'])->where('youtube_live', '==', 0)->orderBy('id', 'desc')->take(4)->get();
            }
        } else if ($type == 'trending') {
            $video = $video->join('recently_viewed_videos', 'videos.id', '=', 'recently_viewed_videos.video_id')->where('recently_viewed_videos.created_at', '>', Carbon::now()->subDays(30))->selectRaw('videos.*,count("video_id") as count')->groupBy('recently_viewed_videos.video_id')->where('youtube_live', '==', 0)->orderBy('count', 'desc')->take(10)->get();
            foreach ($video as $k => $v) {
                $video [$k] ['is_favourite'] = $v->authfavourites()->get()->count();
            }
        }
        return $video;
    }

    /**
     * function to get upcomming live videos
     *
     * @return mixed
     */
    public function getLiveVideos($live = '', $getCount = 9, $paginate = true)
    {
        if ($live) {
            $videos = $this->video->whereliveVideo()->orderBy('scheduledStartTime', 'asc')->get();
        } else {
            if ($this->request->header('x-request-type') == 'mobile') {
                $videos = $this->video->whereliveVideo()->orderBy('scheduledStartTime', 'asc')->take(5)->get();
            } else {
                $videos = $this->video->whereliveVideo()->orderBy('scheduledStartTime', 'asc');
                if ($paginate) {
                    $videos = $videos->paginate($getCount)->toArray();
                } else {
                    $videos = $videos->take($getCount)->get();
                }
            }
        }
        return $videos;
    }
}