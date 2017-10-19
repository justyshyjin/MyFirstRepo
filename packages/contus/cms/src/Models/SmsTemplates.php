<?php

namespace Contus\Cms\Models;

use Contus\Base\Model;

class SmsTemplates extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'name','subject','content','is_active' ];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sms_templates';

    /**
     * funtion to automate operations while Saving
     */
    public function bootSaving() {
        $this->setDynamicSlug ( 'name' );
    }
}
