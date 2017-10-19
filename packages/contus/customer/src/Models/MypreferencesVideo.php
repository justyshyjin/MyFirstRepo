<?php

/**
 * MyprefencesVideo Model
 * To manage the mypreferencevideos table
 *
 * @version 1.0
 * @author Contus Team <developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Customer\Models;

use Contus\Base\Model;
use Contus\Video\Models\Category;
use Contus\Video\Models\Collection;

class MypreferencesVideo extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mypreferences_videos';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'category_id','type','user_id','order' ];
    
    /**
     * Constructor method
     * This is the common method to set hidden only for Front end customers
     */
    public function __construct() {
        parent::__construct ();
        $this->setHiddenCustomer ( [ 'id','is_active','created_at','updated_at' ] );
    }
    /**
     * relation ship between category and mypreference video table
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function preference_category() {
        return $this->belongsTo ( Category::class, 'category_id', 'id' )->where('is_active',1);
    }
    /**
     * relation ship between collection and mypreference video table
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function preference_exams() {
        return $this->belongsTo ( Collection::class, 'category_id', 'id' );
    }
}
