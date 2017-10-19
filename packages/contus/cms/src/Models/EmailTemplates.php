<?php

namespace Contus\Cms\Models;

use Contus\Base\Model;

class EmailTemplates extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'email_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'name','slug','subject','content','is_active' ];

    /**
     * funtion to automate operations while Saving
     */
    public function bootSaving() {
        $this->setDynamicSlug ( 'name' );
    }
}
