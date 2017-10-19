<?php

namespace Contus\Cms\Models;

use Contus\Base\Model;

class StaticPages extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'static_pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'title','content','is_active','banner_image' ];

    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'id','is_active' ] );
    }

    /**
     * funtion to automate operations while Saving
     */
    public function bootSaving() {
        $this->setDynamicSlug ( 'title' );
    }
}
