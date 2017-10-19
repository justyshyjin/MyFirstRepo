<?php

/**
 * VideoCast Models.
 *
 * @name VideoCast
 * @vendor Contus
 * @package Video
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Models;

use Contus\Base\Model;
use Contus\Base\Contracts\AttachableModel;
use Symfony\Component\HttpFoundation\File\File;

class VideoCast extends Model implements AttachableModel {
    
    /**
     * The database table used by the model.
     *
     * @vendor Contus
     * 
     * @package Video
     * @var string
     */
    protected $table = 'video_cast';
    
    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     * 
     * @package Video
     * @var array
     */
    protected $fillable = [ 'name','role','video_id' ];
    
    /**
     * Constructor method
     * sets visible for customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setVisibleCustomer ( [ 'name','role','image_url' ] );
    }
    
    /**
     * Get File Information Model
     * the model related for holding the uploaded file information
     *
     * @vendor Contus
     * 
     * @package Base
     * @return Contus\Base\Model\Video
     */
    public function getFileModel() {
        return $this;
    }
    /**
     * Set the file to Staplaer
     *
     * @param \Symfony\Component\HttpFoundation\File\File $file 
     * @param string $config 
     * @return void
     */
    public function setFile(File $file, $config) {
        $this->image_url = url ( "$config->storage_path/" . $file->getFilename () );
        $this->image_path = $file->getPathname ();
        
        return $this;
    }
    /**
     * Store the file information to database
     * if attachment model is already has record will update
     *
     * @param
     * array Array of instasnces of VideoPoster model.
     * @return boolean
     */
    public function upload(VideoCast $videoCast) {
        return $videoCast->save ();
    }
}
