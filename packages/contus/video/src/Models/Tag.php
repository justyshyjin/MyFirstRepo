<?php

/**
 * Tag Models.
 *
 * @name Tag
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Base\Model;

class Tag extends Model {

    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Video
     * @var string
     */
    protected $table = 'tags';

    /**
     * Constructor method
     * sets visible for customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setVisibleCustomer ( [ 'name','id' ] );
    }

    /**
     * belongsToMany relationship between video and video_tag
     */
    public function videos() {
        return $this->belongsToMany ( Video::class, 'video_tag', 'tag_id', 'video_id' );
    }
}