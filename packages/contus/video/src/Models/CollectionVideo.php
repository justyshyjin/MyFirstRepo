<?php

/**
 * CollectionVideo Models.
 *
 * @name       CollectionVideo
 * @vendor     Contus
 * @package    Video
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Video\Models;

use Illuminate\Database\Eloquent\Model;
use Contus\Base\Helpers\StringLiterals;

class CollectionVideo extends Model {
  
  /**
   * The database table used by the model.
   *
   * @vendor     Contus
   * @package    Video
   * @var string
   */
  protected $table = 'collections_videos';
}
