<?php

namespace Contus\Cms\Models;

use Contus\Base\Model;

class Banner extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'banners';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'title','type','extension','url','banner_image' ];

    /**
     * The attributes that are mass assignable for url generation.
     *
     * @var array
     */
    protected $url = [ 'banner_image' ];
    /**
     * Constructor method
     * This is the common method to set hidden only for Front end customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'id','is_active','updator_id','creator_id','created_at','updated_at' ] );
    }

    /**
     * function to automate operations while Saving
     */
    public function bootSaving() {
        $this->saveImage ( 'banner_image' );        
        $keys = array('dashboard_banner_image');
        $this->clearCache($keys);
    }
}