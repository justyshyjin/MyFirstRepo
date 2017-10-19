<?php

/**
 * Collection Repository
 *
 * To manage the functionalities related to the Collection module from Collection Controller
 *
 * @name QuestionanswersRepository
 * @vendor Contus
 * @package Collection
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Video\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Video\Models\Video;
use Contus\Video\Models\Question;
use Illuminate\Support\Facades\Config;
use Contus\Video\Models\Answer;
use Contus\Notification\Repositories\NotificationRepository;

class QuestionsRepository extends BaseRepository
{
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $questions;

    /**
     * Construct method
     *
     * @param Contus\Video\Models\Collection $collection
     */
    public function __construct(Question $question, NotificationRepository $notificationRepository)
    {
        parent::__construct();
        $this->questions = $question;
        $this->notification = $notificationRepository;
    }

    /**
     * Method to add comment by validating the user
     *
     * @return number
     */
    public function addQuestion()
    {
        $this->setRule('questions', 'filled');
        if ($this->_validate()) {

            $this->questions->questions = $this->request->question;
            $this->questions->video_id = $this->request->video_id;
            if (config()->get('auth.providers.users.table') == 'users') {
                $this->questions->user_type = 'admin';
                $this->questions->user_id = $this->authUser->id;
            } else {
                $this->questions->user_type = 'customer';
                $this->questions->customer_id = $this->authUser->id;
            }
            $this->questions->creator_id = $this->authUser->id;
            return ($this->questions->save()) ? 1 : 0;
        }
    }

    /**
     * Method to add comment by validating the user based on parent comment
     *
     * @return number
     */
    public function addChildQuestion()
    {
        $this->setRules(['question' => 'required'], ['parent_id' => 'required']);
        $this->_validate();
        $this->questions = $this->questions->find($this->request->parent_id);
        if (is_object($this->questions) && !empty ($this->questions->id)) {
            $attachComment = new Answer ();
            $attachComment->answers = $this->request->question;
            if (config()->get('auth.providers.users.table') == 'users') {
                $attachComment->user_type = 'admin';
                $attachComment->user_id = $this->authUser->id;
            } else {
                $attachComment->user_type = 'customer';
                $attachComment->customer_id = $this->authUser->id;
            }
            $attachComment->creator_id = $this->authUser->id;
            $return = ($this->questions->ReplyAnswer()->save($attachComment)) ? 1 : 0;
            $this->notification->notify('answer', $this->questions->id);
            return $return;
        }
    }

    /**
     * Method to add notification to user and admin based on Questions and the person who is answer the questions
     *
     * @return number
     */
    public function setNotification($questions)
    {
        $videos = new Video ();
        $videos = $videos->find($this->request->video_id);
        if ($this->request->has('parent_id') && $this->request->has('question')) {
            $commentLists = $questions->ReplyAnswer();
        } else if ($this->request->has('question')) {
            $commentLists = new Question ();
            $commentLists = $commentLists->where('video_id', $this->request->video_id);
        }
        if ($questions->user_type == 'customer') {
            $commentLists = $commentLists->where('user_id', 0)->where('customer_id', '!=', $questions->customer_id)->groupBy('customer_id')->get();
            foreach ($commentLists as $comment) {
                $notificationUser = ['type' => 'customer', 'id' => $comment->customer_id];
                $notificationcomments = $this->authUser->name . ' has commented on the video ' . $videos->title;
                $this->notification->addNotifications($notificationUser, $notificationcomments, 'video', $videos->id);
            }
            $notificationUser = ['type' => 'admin', 'id' => 1];
            $notificationcomments = $this->authUser->name . ' has commented on the video ' . $videos->title;
            $this->notification->addNotifications($notificationUser, $notificationcomments, 'video', $videos->id);
        } else if ($questions->user_type == 'admin') {
            $commentLists = $commentLists->where('user_id', 0)->groupBy('customer_id')->get();
            foreach ($commentLists as $comment) {
                $notificationUser = ['type' => 'customer', 'id' => $comment->customer_id];
                $notificationcomments = $this->authUser->name . ' has commented on the video ' . $videos->title;
                $this->notification->addNotifications($notificationUser, $notificationcomments, 'video', $videos->id);
            }
        }
    }

    /**
     * Function to get all Questions
     *
     * @return object
     */
    public function getAllQa()
    {
        return $this->questions->get();
    }

    /**
     * Function to update status for questions
     *
     * @return object
     */
    public function updateStatus($id, $status)
    {
        $question = $this->questions = $this->questions->find($id);
        $question->is_active = $status;
        $return = $question->save();
        $this->notification->notify('question', $id);
        return ($return) ? 1 : 0;
    }

    /**
     * Get headings for grid
     *
     * @return array
     */
    public function getGridHeadings()
    {
        return ['heading' => [['name' => trans('video::videos.name'), 'value' => 'name', 'sort' => true], ['name' => trans('video::videos.student'), 'value' => '', 'sort' => false], ['name' => 'Questions', 'value' => '', 'sort' => false], ['name' => trans('video::playlist.status'), 'value' => 'is_active', 'sort' => false], ['name' => trans('video::collection.added_on'), 'value' => '', 'sort' => false]]];
    }

    /**
     * Prepare grid function
     *
     * @return array
     */
    public function prepareGrid()
    {
        $this->setGridModel($this->questions)->setEagerLoadingModels(['video' => function ($query) {
            $query->get();
        }, 'customer' => function ($query) {
            $query->get();
        }]);
        return $this;
    }

    /**
     * Function to apply filter for search of Comments grid
     *
     * @param mixed $searchComment
     * @return \Illuminate\Database\Eloquent\Builder $searchComment The builder object of comments grid.
     */
    protected function searchFilter($searchComment)
    {
        $searchRecordGroup = $this->request->has('searchRecord') && is_array($this->request->input('searchRecord')) ? $this->request->input('searchRecord') : [];
        $title = $is_active = null;
        extract($searchRecordGroup);
        if ($title) {
            $searchComment = $searchComment->where('title', 'like', '%' . $title . '%');
        }
        if (is_numeric($is_active)) {
            $searchComment = $searchComment->where('is_active', $is_active);
        }
        return $searchComment;
    }
}