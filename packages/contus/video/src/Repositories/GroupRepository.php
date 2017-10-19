<?php

/**
 * Group Repository
 *
 * To manage the functionalities related to videos
 *
 * @name VideoRepository
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Video\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Video\Models\Group;
use Contus\Video\Models\Video;
use Contus\Customer\Models\MypreferencesVideo;
use Illuminate\Support\Facades\DB;
use Contus\Video\Models\Collection;
use Illuminate\Support\Facades\Cache;

class GroupRepository extends BaseRepository
{

    /**
     * Constructor method
     *
     * @vendor Contus
     *
     * @package Video
     * @param Playlist $play
     */
    public function __construct(Group $group, MypreferencesVideo $mypreference)
    {
        parent::__construct();
        $this->_group = $group;
        $this->_preference = $mypreference;
    }

    /**
     * Funtion to add or update playlist details
     *
     * @vendor Contus
     *
     * @package Video
     * @param int $id
     * @return boolean
     */
    public function addOrUpdateGroup($id = null)
    {
        if (!empty ($id)) {
            $group = $this->_group->find($id);
            if (!is_object($group)) {
                return false;
            }
            $this->setRules(['name' => 'sometimes|required|max:255', 'is_active' => 'sometimes|required|boolean', 'order' => 'sometimes|required']);
            $group->updator_id = $this->authUser->id;
        } else {
            $this->setRules(['name' => 'required', 'is_active' => 'required|boolean', 'order' => 'required']);
            $group = new Group ();
            $group->is_active = 1;
            $group->is_active = 1;
        }
        $this->_validate();
        $group->fill($this->request->except('_token'));
        $this->_group = $group;
        $group->save();
        return true;
    }

    /**
     * Fetch all the playlist records using pagination
     *
     * @vendor Contus
     *
     * @package Video
     * @return array
     */
    public function getAllPlaylist()
    {
        return $this->_group->paginate(10)->toArray();
    }

    /**
     * Get headings for grid
     *
     * @vendor Contus
     *
     * @package Video
     * @return array
     */
    public function getGridHeadings()
    {
        return ['heading' => [['name' => trans('video::playlist.group_name'), 'value' => 'name', 'sort' => true], ['name' => trans('video::playlist.group_image'), 'value' => '', 'sort' => false], ['name' => trans('video::playlist.group_order'), 'value' => '', 'sort' => false], ['name' => trans('video::playlist.status'), 'value' => 'is_active', 'sort' => false], ['name' => trans('video::collection.added_on'), 'value' => '', 'sort' => false], ['name' => trans('video::collection.action'), 'value' => '', 'sort' => false]]];
    }

    /**
     * Get headings for grid
     *
     * @vendor Contus
     *
     * @package Video
     * @return array
     */
    public function prepareGrid()
    {
        $this->setGridModel($this->_group)->setEagerLoadingModels(['group_videos' => function ($query) {
            $query->where('is_archived', 0);
        }]);
        return $this;
    }

    /**
     * Function to apply filter for search of Playlists grid
     *
     * @param mixed $builderPlaylists
     * @return \Illuminate\Database\Eloquent\Builder $builderPlaylists The builder object of collections grid.
     */
    protected function searchFilter($builderPlaylists)
    {
        $searchRecordGroups = $this->request->has('searchRecord') && is_array($this->request->input('searchRecord')) ? $this->request->input('searchRecord') : [];
        $title = $is_active = null;
        extract($searchRecordGroups);
        if ($title) {
            $builderPlaylists = $builderPlaylists->where('name', 'like', '%' . $title . '%');
        }
        if (is_numeric($is_active)) {
            $builderPlaylists = $builderPlaylists->where('is_active', $is_active);
        }
        return $builderPlaylists;
    }

    /**
     * Funtion to get all groups from exam id or slug
     *
     *
     * @param string|id $examId
     * @return object
     */
    public function getAllVideos($groupId)
    {
        $sgroup = $this->_group->whereIn($this->getKeySlugorId(), explode(",", $groupId))->with('exams')->where('is_active', 1)->first();
        $group = (is_object($sgroup)) ? clone $sgroup : $this->throwJsonResponse();
        if (($this->request->header('x-request-type') !== 'mobile')) {
            if (Cache::has('groupList' . $group->slug) || ($this->request->input('page') > 1)) {
                $group = Cache::rememberForever('groupList' . $group->slug, function () use ($group) {
                    if (Cache::has('cache_keys_playlist')) {
                        $previouscache = Cache::get('cache_keys_playlist');
                        if (!(strpos($previouscache, 'groupList' . $group->slug) !== false)) {
                            Cache::put('cache_keys_playlist', $previouscache . ',groupList' . $group->slug, 0);
                        }
                    } else {
                        Cache::put('cache_keys_playlist', 'groupList' . $group->slug, 0);
                    }
                    $group = $group->group_videos();
                    $group = $group->with(['categories' => function ($q) {
                        $q->addSelect('title');
                    }]);
                    $group = $group->select('videos.id', 'selected_thumb', 'slug', 'title', 'video_duration')->orderBy('video_order', 'asc')->get()->toArray();
                    return ['next_page_url' => null, 'data' => $group];
                });
            } else {
                $group = $group->group_videos()->selectRaw('videos.id');
                $group = $group->with(['categories' => function ($q) {
                    $q->addSelect('title');
                }]);
                $group = $group->orderBy('video_order', 'asc')->paginate(5)->toArray();
            }
        } else {
            $group = $group->group_videos()->leftJoin('favourite_videos as f1', function ($j) {
                $j->on('videos.id', '=', 'f1.video_id')->on('f1.customer_id', '=', DB::raw((auth()->user()) ? auth()->user()->id : 0));
            })->selectRaw('videos.*,count(f1.video_id) as is_favourite')->groupBy('videos.id');
            $group = $group->orderBy('video_order', 'asc')->paginate(10)->toArray();
        }
        $group ['group_id'] = $sgroup->toArray();
        return $group;
    }

    /**
     * Function to get all the recommended videos
     *
     * @return array
     */
    public function getRecommendedVideos($skip = '')
    {
        $exams = "";
        $exams = auth()->user()->exams()->where('is_active', 1)->pluck('collections.id')->toArray();
        if (!$exams) {
            $exams = Collection::where('is_active', 1)->pluck('id')->toArray();
            auth()->user()->exams()->attach($exams);
        } // groups
        if ($this->request->has('exam') && $this->request->has('group')) {
            $examSlug = $this->request->exam;
            $groupSlug = $this->request->group;
            $exams = Collection::where($this->getKeySlugorId(), $examSlug)->first()->groups()->where($this->getKeySlugorId(), '!=', $groupSlug)->pluck('id')->toArray();
            $video = new Video ();
            return $video->whereCustomer()->join('collections_videos as examvideos', function ($join) use ($exams) {
                $join->on('examvideos.video_id', '=', 'videos.id')->whereIn('group_id', $exams);
            })->selectRaw('videos.*')->groupBy('videos.id')->with('categories.parent_category.parent_category')->paginate(10)->toArray();
        } else {
            $exams = Group::where($this->getKeySlugorId(), '!=', $skip)->whereIn('collection_id', $exams)->where('is_active', 1)->pluck('id')->toArray();
            $video = new Video ();
            return $video->whereCustomer()->join('collections_videos as examvideos', function ($join) use ($exams) {
                $join->on('examvideos.video_id', '=', 'videos.id')->whereIn('group_id', $exams);
            })->selectRaw('videos.*')->groupBy('videos.id')->with('categories.parent_category.parent_category')->paginate(10)->toArray();
        }
    }
}