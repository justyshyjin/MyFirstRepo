<?php

/**
 * VideoCastRepository
 *
 * To manage the functionalities related to the VideoCast module
 * 
 * @name VideoCastRepository
 * @vendor Contus
 * @package video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Video\Models\VideoCast;
use Contus\Base\Repositories\UploadRepository;

class VideoCastRepository extends BaseRepository {
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_videoCast;
    /**
     * Class property to hold the videoCastCollection
     *
     * @var array
     */
    protected $videoCastCollection = [ ];
    /**
     * class property to hold the instance of UploadRepository
     *
     * @var \Contus\Base\Repositories\UploadRepository
     */
    public $uploadRepository;
    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package Video
     * @param \Contus\Video\Models\VideoCast $videoCast 
     * @param \Contus\Base\Repositories\UploadRepository $uploadRepository 
     *
     * @return void
     */
    public function __construct(VideoCast $videoCast, UploadRepository $uploadRepository) {
        parent::__construct ();
        $this->_videoCast = $videoCast;
        $this->uploadRepository = $uploadRepository;
    }
    /**
     * Function to save countries and videos map data.
     *
     * @return string The hierarchy string.
     */
    public function syncVideoCast($video, $casts) {
        $video->videoCast->each ( function ($videoCast) {
            $this->videoCastCollection [$videoCast->id] = $videoCast;
        } );
        
        foreach ( $casts as $key => $cast ) {
            if (! empty ( $cast ['cast_name'] ) && ! empty ( $cast ['cast_role'] )) {
                if (isset ( $cast ['id'] ) && array_key_exists ( $cast ['id'], $this->videoCastCollection )) {
                    $videoCast = $this->videoCastCollection [$cast ['id']];
                    unset ( $this->videoCastCollection [$cast ['id']] );
                } else {
                    $videoCast = new VideoCast ();
                    $videoCast->video_id = $video->id;
                }
                
                $videoCast->name = $cast ['cast_name'];
                $videoCast->role = $cast ['cast_role'];
                if (isset ( $cast ['image_url'] )) {
                    $videoCast->image_url = $cast ['image_url'];
                }
                if (isset ( $cast ['image_path'] )) {
                    $videoCast->image_path = $cast ['image_path'];
                }
                
                if ($videoCast->save () && isset ( $cast ['image'] )) {
                    $this->uploadRepository->setModelIdentifier ( UploadRepository::MODEL_IDENTIFIER_CAST_IMAGE )->setRequestParamKey ( 'cast.' . $key . '.image' )->setConfig ();
                    $this->uploadRepository->handleUpload ( $videoCast );
                }
            }
        }
        
        if (! empty ( $this->videoCastCollection )) {
            $this->_videoCast->whereIn ( 'id', array_keys ( $this->videoCastCollection ) )->delete ();
        }
    }
}