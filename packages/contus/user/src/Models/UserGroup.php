<?php

/**
 * AdminGroup
 *
 * To manage the functionalities related to Admingroup
 * @name       AdminGroup
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\User\Models;

use Illuminate\Database\Eloquent\Model;
use Contus\User\Models\User;

class UserGroup extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_groups';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'permissions'];
    
    /**
     * HasMany relationship between user_groups and users
     */
    public function users() {
        return $this->hasMany ( User::class, 'user_group_id', 'id' );
    }
}
