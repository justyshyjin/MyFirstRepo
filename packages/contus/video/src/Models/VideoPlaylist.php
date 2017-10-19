<?php

/**
 * Video Model for videos table in database
 *
 * @name       Video
 * @vendor     Contus
 * @package    Video
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Base\Model;
use Contus\Video\Models\TranscodedVideo;
use Contus\Base\Contracts\AttachableModel;
use Symfony\Component\HttpFoundation\File\File;
use Contus\Video\Models\VideoCategory;
use Contus\Base\Helpers\StringLiterals;
use Contus\Video\Models\VideoCountries;
use Contus\Video\Models\VideoPoster;

class VideoPlaylist extends Model{
   /**
    * The database table used by the model.
    *
    * @vendor Contus
    *
    * @package Video
    * @var string
    */
   protected $table = 'video_playlists';

}

