<?php

/**
 * Video Category Model for video_categories table in database
 *
 * @name VideoCategory
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Base\Model;
use Contus\Video\Models\Video;
use Contus\Video\Models\Category;
use Illuminate\Support\Facades\Config;

class VideoCategory extends Model {
    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Video
     * @var string
     */
    protected $table = 'video_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package Video
     * @var array
     */
    protected $fillable = [ 'video_id','category_id' ];

    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'id','video_id','category_id','created_at','updated_at' ] );
    }

    /**
     * Belongsto relationship between video_categories and videos
     */
    public function video() {
        return $this->belongsTo ( Video::class, 'video_id' )->select ( 'id', 'title' );
    }
    /**
     * Belongsto relationship between video_categories and categories
     */
    public function category() {
        return $this->belongsTo ( Category::class, 'category_id' );
    }
}

