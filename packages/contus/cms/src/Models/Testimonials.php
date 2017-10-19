<?php

namespace Contus\Cms\Models;

use Contus\Base\Model;

class Testimonials extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'testimonials';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'name','description','image' ];

    /**
     * The attributes that are mass assignable for url generation.
     *
     * @var array
     */
    protected $url = [ 'image' ];

    /**
     * Constructor method
     * This is the common method to set hidden only for Front end customers
     */
    public function __construct() {
      parent::__construct ();
      $this->setHiddenCustomer ( [ 'id','is_active','updator_id','creator_id','created_at','updated_at'] );
    }

    /**
     * funtion to automate operations while Saving
     */
    public function bootSaving() {
        $this->saveImage ( 'image' );
    }
}
