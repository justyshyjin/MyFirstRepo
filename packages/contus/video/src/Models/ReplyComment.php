<?php

/**
 * RepyComment Models.
 *
 * @name RepyComment
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Illuminate\Database\Eloquent\Model;
use Contus\Video\Models\Comment;
use Contus\User\Models\User;
use Contus\Customer\Models\Customer;

class ReplyComment extends Model {

    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Video
     * @var string
     */
    protected $table = 'reply_comments';
    /**
     * Hidden variable to be returned
     *
     * @vendor Contus
     *
     * @package Video
     * @var array
     */
    protected $hidden = [ 'video_id','user_id','customer_id','creator_id','updator_id','updated_at' ];

    /**
     * belongsToMany relationship between video and collections_videos
     */
    public function replycomments() {
        return $this->belongsTo ( Comment::class );
    }

    /**
     * Belongs to relationship between user and comments
     */
    public function admin() {
        return $this->belongsTo ( User::class , 'user_id'  )->select ( 'id', 'name', 'email', 'phone','profile_image as profile_picture' );
    }

    /**
     * Belongs to relationship between customer and comments
     */
    public function customer() {
        return $this->belongsTo ( Customer::class )->select ( 'id', 'name', 'email', 'phone', 'profile_picture' );
    }
}
