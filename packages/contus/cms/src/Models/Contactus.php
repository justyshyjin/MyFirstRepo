<?php

namespace Contus\Cms\Models;

use Contus\Base\Model;

class Contactus extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'contact_us';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'name','email','phone','message' ];

    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct() {
      parent::__construct ();
      $this->setHiddenCustomer ( [ 'id','is_active' ] );
    }
}