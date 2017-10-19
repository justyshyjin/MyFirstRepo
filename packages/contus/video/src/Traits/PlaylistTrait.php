<?php

/**
 * Playlist Trait
 *
 * To manage the functionalities related to the Categories module from Categories Controller
 *
 * @vendor Contus
 *
 * @package Categories
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Traits;

trait PlaylistTrait {
    /**
     * Get headings for grid
     *
     * @vendor Contus
     *
     * @package Video
     * @return array
     */
    public function getGridHeadings() {
        return [ 'heading' => [ [ 'name' => trans ( 'video::playlist.playlist_name' ),'value' => 'name','sort' => true ],[ 'name' => trans ( 'video::collection.no_of_videos' ),'value' => '','sort' => false ],[ 'name' => trans ( 'video::collection.status' ),'value' => 'is_active','sort' => false ],[ 'name' => trans ( 'video::collection.added_on' ),'value' => '','sort' => false ],[ 'name' => trans ( 'video::collection.action' ),'value' => '','sort' => false ] ] ];
    }
    /**
     * Get headings for grid
     *
     * @vendor Contus
     *
     * @package Video
     * @return array
     */
    public function prepareGrid() {
        $this->setGridModel ( $this->_playlist )->setEagerLoadingModels ( [ 'videos' => function ($query) {
            $query->where ( 'is_archived', 0 );
        },'category' ] );
        return $this;
    }
}