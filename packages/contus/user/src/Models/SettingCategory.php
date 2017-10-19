<?php

/**
 * SettingCategory
 *
 * To manage the functionalities related to SettingCategory
 *
 * @name SettingCategory
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\User\Models;

use Contus\Base\Model;

class SettingCategory extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'setting_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'name','slug','parent_id','description' ];

    /**
     * Method used to retrive the settings with hasMany relation.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function settings() {
        return $this->hasMany ( Setting::class );
    }

    /**
     * Method used to retrive the setting category with hasMany relation.
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function category() {
        return $this->hasMany ( SettingCategory::class, 'parent_id' );
    }
}
