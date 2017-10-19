<?php

namespace Contus\Cms\Models;

use Contus\Base\Model;

class LatestNews extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'latest_news';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'title','content','is_active','post_creator','post_image' ];

    /**
     * The attributes that are mass assignable for url generation.
     *
     * @var array
     */
    protected $url = [ 'post_image' ];
    /**
     * Constructor method
     * This is the common method to set hidden only for Front end customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'id','is_active','updator_id','creator_id','updated_at'] );
    }
    /**
     * funtion to automate operations while Saving
     */
    public function bootSaving() {
        $this->setDynamicSlug ( 'title' );
        $this->saveImage ( 'post_image' );
    }
}
