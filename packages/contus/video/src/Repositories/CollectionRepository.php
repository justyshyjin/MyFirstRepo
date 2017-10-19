<?php

/**
 * Collection Repository
 *
 * To manage the functionalities related to the Collection module from Collection Controller
 *
 * @name CollectionRepository
 * @vendor Contus
 * @package Collection
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 *
 */
namespace Contus\Video\Repositories;

use Contus\Video\Contracts\ICollectionRepository;
use Contus\Video\Models\Collection;
use Contus\Base\Repository as BaseRepository;
use Contus\Video\Models\Video;
use Contus\Base\Helpers\StringLiterals;
use Contus\Video\Models\CollectionVideo;
use Contus\Video\Models\Playlist;
use Illuminate\Support\Facades\DB;

class CollectionRepository extends BaseRepository implements ICollectionRepository {
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_collection;
    /**
     * Class property to hold the key which hold the group name requested
     *
     * @var string
     */
    protected $requestedCollection = 'q';
    /**
     * Class property to hold the key which hold the CollectionVideo object
     *
     * @var object
     */
    protected $collectionVideo;
    /**
     * Construct method
     *
     * @param Collection $collection
     * @param PlaylistRepository $playlistrepository
     * @param video $friendvideo
     */
    public function __construct(Collection $collection, PlaylistRepository $playlistrepository, video $friendvideo) {
        parent::__construct ();
        $this->_collection = $collection;
        $this->_playlist = $playlistrepository;
        $this->_video = $friendvideo;
        $this->collectionVideo = new CollectionVideo ();
        $this->setRules ( [ StringLiterals::TITLE => 'required_if:id,0|unique:collections',StringLiterals::ORDER => 'required' ] );
    }
    /**
     * Store a newly created collection.
     *
     * @param int $id input
     *
     * @return boolean
     */
    public function addOrUpdateCollection($id = null) {
        if ($this->request->id) {
            $collection = $this->_collection->find ( $this->request->id );
            if ($id) {
                $this->setRule ( 'title', 'required|unique:collections,title,' . $this->request->id );
                $this->_validate ();
                $collection->fill ( $this->request->except ( StringLiterals::TOKEN ) );
                $collection->save ();
            }

            /**
             * Check if video ids are also sent in the request.
             * If yes then associate them with the collection.
             */
            if (isset ( $this->request->selectedVideos )) {
                /**
                 * Check and filter the videos selected that are already added to the collection.
                 */
                $existingVideos = CollectionVideo::where ( 'collection_id', $this->request->id )->whereIn ( 'video_id', $this->request->selectedVideos )->lists ( 'video_id' )->toArray ();
                $filteredArray = array_diff ( $this->request->selectedVideos, $existingVideos );
                if (! empty ( $filteredArray )) {
                    $selectedVideos = Video::whereIn ( 'id', $filteredArray )->with ( 'categories' )->get ();
                    foreach ( $selectedVideos as $videos ) {
                        $collection->videos ()->attach ( [ $videos->id => [ 'category_id' => $videos->categories ()->first ()->id ] ] );
                    }
                }
            }
            return true;
        } else {
            $collection = new Collection ();
            $this->setMessage ( 'title.required_if', 'The CollectionName filed is required.' );
            $this->_validate ();
            if ($this->request->title != null) {
                $collection->fill ( $this->request->except ( StringLiterals::TOKEN, 'selectedVideos' ) );
                $collection->save ();
                $selectedVideos = Video::whereIn ( 'id', $this->request->selectedVideos )->lists ( 'id' )->toArray ();
                $collection->videos ()->attach ( $selectedVideos );
                return true;
            }
        }
    }
    /**
     * function to create a new collection
     *
     * @return boolean
     */
    public function createCollection() {
        $collection = new Collection ();
        $this->setRules ( [ StringLiterals::TITLE => 'required|unique:collections,title' ] );
        $this->setMessage ( 'title', 'The Exam  filed is required.' );
        $this->_validate ();
        if ($this->request->title != null) {
            $collection->fill ( $this->request->except ( StringLiterals::TOKEN ) );
            $collection->save ();
            return true;
        }
    }

    /**
     * function to Get all the collections
     *
     * @return array
     */
    public function getAllCollection() {
        $exams = $this->_collection->has ( 'groups' )->get ();
        $lists = [ ];
        foreach ( $exams as $exam ) {
            $lists = array_merge ( $lists, $exam->groups ()->selectRaw ( 'CONCAT("' . $exam->title . ' > ",name) as  title,id' )->pluck ( 'id', 'title' )->toArray () );
        }
        return array_flip ( $lists );
    }

    /**
     * function to get the exam name for group
     *
     * @return object
     */
    public function getAllCollectionName() {
        return $this->_collection->where ( 'is_active', 1 )->select ( 'id', StringLiterals::TITLE )->get ();
    }

    /**
     * Fetch users to display in admin block.
     *
     * @return object
     */
    public function getCollections($status) {
        return $this->_collection->filter ( $status )->paginate ( 10 );
    }
    /**
     * Fetch user to edit.
     *
     * @return object
     */
    public function getCollection($id) {
        return $this->_collection->find ( $id );
    }
    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     *
     * @return object
     */
    public function prepareGrid() {
        $this->setGridModel ( $this->_collection )->setEagerLoadingModels ( 'groups' );
        return $this;
    }
    /**
     * Get headings for grid
     *
     * @return array
     */
    public function getGridHeadings() {
        return [ StringLiterals::GRIDHEADING => [ [ 'name' => trans ( 'video::collection.exam_name' ),StringLiterals::VALUE => StringLiterals::TITLE,'sort' => true ],[ 'name' => trans ( 'video::collection.order' ),StringLiterals::VALUE => '','sort' => true ],[ 'name' => trans ( 'video::collection.status' ),StringLiterals::VALUE => StringLiterals::ISACTIVE,'sort' => false ],[ 'name' => trans ( 'video::collection.added_on' ),StringLiterals::VALUE => '','sort' => false ],[ 'name' => trans ( 'video::collection.action' ),StringLiterals::VALUE => '','sort' => false ] ] ];
    }

    /**
     * Function to apply filter for search of Collections grid
     *
     * @param mixed $builderCollections
     * @return object.
     */
    protected function searchFilter($builderCollections) {
        $searchRecordCollections = $this->request->has ( StringLiterals::SEARCHRECORD ) && is_array ( $this->request->input ( StringLiterals::SEARCHRECORD ) ) ? $this->request->input ( StringLiterals::SEARCHRECORD ) : [ ];
        $title = $is_active = null;
        extract ( $searchRecordCollections );
        /**
         * Check if the title of the collection is present in the collection search.
         * If yes, then use it in filter.
         */
        if ($title) {
            $builderCollections = $builderCollections->where ( StringLiterals::TITLE, 'like', '%' . $title . '%' );
        }

        /**
         * Check if the status of the collection is present in the collection search.
         * If yes, then use it in filter.
         */
        if (is_numeric ( $is_active )) {
            $builderCollections = $builderCollections->where ( StringLiterals::ISACTIVE, $is_active );
        }
        return $builderCollections;
    }

    /**
     * Get the collection edit rules
     *
     * @return array
     */
    public function getEditRules() {
        return $this->setRules ( [ StringLiterals::TITLE => 'required|unique:collections',StringLiterals::ORDER => 'required' ] )->getRules ();
    }

    /**
     * Check the collection name provied is unique.
     * check only if the request has the expected param
     *
     * @param int $id
     * @return boolean
     */
    public function isUniqueCollection($id = null) {
        if ($this->request->has ( $this->requestedCollection )) {
            $uniqueQuery = $this->_collection->where ( 'title', $this->request->get ( $this->requestedCollection ) );
            if ($id) {
                $uniqueQuery->where ( 'id', '!=', $id );
            }

            return $uniqueQuery->count () == 0;
        }
        return false;
    }
    /**
     * Repository function to get the collection related videos list
     *
     * @param integer $id
     * @return variable
     */
    public function getVideoCollections($id) {
        $this->_collection = $this->_collection->find ( $id );
        if (is_null ( $this->_collection )) {
            return $this->_collection;
        }
        return [ 'collection' => $this->_collection,'videos' => $this->_collection->videos ()->with ( [ 'videocategory.category','recent' ] )->where ( 'is_archived', 0 )->paginate ( 10 )->toArray () ];
    }

    /**
     * Repository function to get the Exam related videos list
     *
     * @param integer $id
     * @return variable
     */
    public function getExamVideoCollections($id = '') {
        if ($id) {
            $this->_collection = $this->_collection->find ( $id );
            if (is_null ( $this->_collection )) {
                return $this->_collection;
            }
            return [ 'exams' => $this->_collection,'exams_videos' => $this->_collection->videos ()->where ( 'is_archived', 0 )->whereIn ( 'is_subscription', ((auth ()->user () && auth ()->user ()->isExpires ()) ? [ [ 0 ],[ 1 ] ] : [ 0 ]) )->leftJoin ( 'favourite_videos as f1', function ($j) {
                $j->on ( 'videos.id', '=', 'f1.video_id' )->on ( 'f1.customer_id', '=', DB::raw ( (auth ()->user ()) ? auth ()->user ()->id : 0 ) );
            } )->selectRaw ( 'videos.*,count(f1.video_id) as is_favourite' )->groupBy ( 'videos.id' )->with ( [ 'videocategory.category' ] )->where ( 'is_archived', 0 )->paginate ( 10 )->toArray () ];
        } else {
            $this->_collection = $this->_collection->has ( 'groups' )->where ( 'is_active', 1 )->orderBy ( 'order', 'asc' )->get ();
            return [ 'allexams' => $this->_collection ];
        }
    }
    /**
     * Function to remove videos in the from collections.
     *
     * @param integer|array $ids
     * The ids of the videos which are to be removed.
     * @return boolean True if the videos are removed successfully and false if not.
     */
    public function removeVideoFromCollection($ids, $collectionId) {

        /**
         * Delete the video by the given id
         */
        $ids = is_array ( $ids ) ? $ids : [ $ids ];

        return empty ( $ids ) ? StringLiterals::LITERALFALSE : $this->collectionVideo->whereIn ( StringLiterals::VIDEOID, $ids )->where ( 'collection_id', $collectionId )->delete ();
    }
    /**
     * function to get all groups from exam id or slug
     *
     * @param string|id $examId
     * @return object
     */
    public function getAllGroups($examId) {
        $groups = Collection::where ( $this->getKeySlugorId (), $examId )->where ( 'is_active', 1 )->first ();
        if (is_object ( $groups )) {
            $group = $groups->groups ()->where ( 'is_active', 1 )->has ( 'group_videos' )->with ( [ 'group_videos' => function ($query) {
                $query->selectRaw ( 'count(videos.id) as count' )->groupBy ( 'group_id' );
            } ] )->orderByRaw ( 'convert(`order`, decimal) desc' )->paginate ( 10 )->toArray ();
            $group ['exam_name'] = $groups->toArray ();
            return $group;
        } else {
            return $this->throwJsonResponse ();
        }
    }
}
