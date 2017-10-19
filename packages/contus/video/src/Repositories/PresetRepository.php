<?php

/**
 * Preset Repository
 *
 * To manage the functionalities related to Presets
 * @name       PresetRepository
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Video\Contracts\IPresetRepository;
use Contus\Video\Models\VideoPreset;
use Contus\Base\Helpers\StringLiterals;

class PresetRepository extends BaseRepository implements IPresetRepository {
 public $videoPreset;
 /**
  * Construct method initialization
  *
  * Validation rule for user verification code and forgot password.
  */
 public function __construct() {
  parent::__construct ();
  $this->videoPreset = new VideoPreset;
 }
 /**
  * Prepare the grid
  * set the grid model and relation model to be loaded
  *
  * @vendor Contus
  *
  * @package Collection
  * @return Contus\Collection\Repositories\Repository
  */
 public function prepareGrid() {
  $this->setGridModel ( $this->videoPreset );
  return $this;
 } 
 
 /**
  * Get headings for grid
  *
  * @vendor Contus
  *
  * @package Collection
  * @return array
  */
 public function getGridHeadings() {
         return [
                 StringLiterals::GRIDHEADING => [
                         [
                                 'name' => trans ( 'video::presets.preset_name' ),
                                 StringLiterals::VALUE => 'name',
                                 'sort' => true
                         ],
                         [
                                 'name' => trans ( 'video::presets.aws_identifier' ),
                                 StringLiterals::VALUE => 'aws_id',
                                 'sort' => false
                         ],
                         [
                                 'name' => trans ( 'video::presets.format' ),
                                 StringLiterals::VALUE => 'format',
                                 'sort' => true
                         ],
                         [
                                 'name' => trans ( 'video::presets.status' ),
                                 StringLiterals::VALUE => 'is_active',
                                 'sort' => false
                         ]
                 ]
         ];
 }
 
 /**
  * apply Search filter
  *
  * @param mixed $builder
  * @return \Illuminate\Database\Eloquent\Builder $builder
  */
 protected function searchFilter($builder) {
     $searchRecord = $this->request->has ( StringLiterals::SEARCHRECORD ) && is_array ( $this->request->input ( StringLiterals::SEARCHRECORD ) ) ? $this->request->input ( StringLiterals::SEARCHRECORD ) : [ ];
     $is_active = $name = $aws_id = $format = null;
     extract ( $searchRecord );
     
     if ($name) {
         $builder = $builder->where ( 'name', 'like', '%' . $name . '%' );
     }
     if ($aws_id) {
         $builder = $builder->where ( 'aws_id', 'like', '%' . $aws_id . '%' );
     }
     if ($format) {
         $builder = $builder->where ( 'format', 'like', '%' . $format . '%' );
     }
     if (is_numeric ( $is_active )) {
         $builder = $builder->where ( StringLiterals::ISACTIVE, $is_active );
     }
 
     return $builder;
 }
 /**
  * Function to get number of active presets.
  *
  * @return integer Number o active presets in the database.
  */
 public function getNumberOfActivePresets() {
     return $this->videoPreset->where('is_active', 1)->count();
 }
}