<?php

/**
 * Transcoded Video Model for videos table in database
 *
 * @name TranscodedVideo
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Base\Model;
use Contus\Video\Models\VideoPreset;

class TranscodedVideo extends Model {

    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package Video
     * @var string
     */
    protected $table = 'transcoded_videos';

    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package Video
     * @var array
     */
    protected $fillable = [ 'video_id','is_active' ];
    /**
     * Constructor method
     * sets visible for customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'id','video_id','is_active','creator_id','updator_id','created_at','updated_at','preset_id' ] );
    }
    /**
     * BelongsTo relationship between video preset and transcoded videos table.
     */
    public function presets() {
        return $this->belongsTo ( VideoPreset::class, 'preset_id' );
    }
}
