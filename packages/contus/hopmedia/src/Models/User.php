<?php

/**
 * User Models is user access a user table data in database
 *
 * @name User
 * @vendor Contus
 * @package User
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Hopmedia\Models;

use Illuminate\Auth\Authenticatable;
use Contus\Base\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Contus\Base\Contracts\AttachableModel as AttachableModel;
use Contus\Base\Helpers\StringLiterals;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, AttachableModel
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @vendor Contus
     *
     * @package User
     * @var string
     */
    protected $table = 'users';
    /**
     * Morph class name
     *
     * @var string
     */
    protected $morphClass = 'users';
    /**
     * Date format
     *
     * @vendor Contus
     *
     * @package User
     * @var string
     */
    protected $dates = ['last_logged_out_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @vendor Contus
     *
     * @package User
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'phone','company','domain','is_active', 'gender', 'user_group_id', 'parent_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @vendor Contus
     *
     * @package User
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    /**
     * The attribute will used to generate url
     *
     * @var array
     */
    protected $url = ['profile_picture', 'profile_image'];

    /**
     * Constructor method
     * sets hidden for customers
     */
    public function __construct()
    {
        parent::__construct();
        $this->setHiddenCustomer(['id', 'password', 'remember_token', 'user_role_id', 'access_token', 'is_active', 'creator_id', 'updator_id', 'created_at', 'updated_at', 'profile_image_path', 'user_group_id']);
    }

    /**
     * Method used to retrive the group with hasMany relation.
     *
     * @vendor Contus
     *
     * @package User
     * @return Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function group()
    {
        return $this->belongsTo(UserGroup::class, 'user_group_id');
    }

    /**
     * Image MorphOne Relation
     *
     * @vendor Contus
     *
     * @package User
     * @return Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function image()
    {
        return $this->morphOne(Attachment::class, 'attachable');
    }

    /**
     * Method used to filter the users based on the request.
     *
     * @vendor Contus
     *
     * @package User
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, $status)
    {
        if ($status == 'active') {
            $query->where(StringLiterals::ISACTIVE, 1);
        } else if ($status == 'in-active') {
            $query->where(StringLiterals::ISACTIVE, 0);
        }
        return $query;
    }

    /**
     * Get Setting For Stapler.
     *
     * @vendor Contus
     *
     * @package User
     * @see https://github.com/CodeSleeve/stapler/blob/master/docs/examples.md
     * @return array
     */
    public function getStaplerSetting()
    {
        return ['styles' => ['thumb' => '120x90']];
    }

    /**
     * Set the file to Staplaer
     *
     * @param \Symfony\Component\HttpFoundation\File\File $file
     * @param string $config
     * @return void
     */
    public function setFile(File $file, $config)
    {
        $this->profile_image = url("$config->storage_path/" . $file->getFilename());
        $this->profile_image_path = $file->getPathname();

        return $this;
    }

    /**
     * Store the file information to database
     * if attachment model is already has record will update
     *
     * @vendor Contus
     *
     * @package User
     * @param
     * $attachment
     * @return Mara\Models\Attachment | boolean
     */
    public function upload(User $user)
    {
        return $user->save();
    }

    /**
     * Get File Information Model
     * the model related for holding the uploaded file information
     *
     * @vendor Contus
     *
     * @package User
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getFileModel()
    {
        return $this;
    }

    /**
     * Checks if the current logged in user has super admin permission
     *
     * @vendor Contus
     *
     * @package User
     * @return boolean
     */
    public function hasSuperAdminAccess()
    {
        if ($this->user_group_id == 1) {
            return true;
        }
        return false;
    }

    /**
     * Checks if the current logged in user is a super admin
     * if yes will allow all access irrespective of permissions
     * if no then check the user has permission to access the current route
     *
     * @vendor Contus
     *
     * @package User
     * @param
     * $route
     *
     * @return boolean
     */
    public function hasAccess($route)
    {
        $configRoutes = config()->get('access.permissionRoutes');
        if ($this->hasSuperAdminAccess()) {
            return true;
        } else {
            $routeMethod = $this->routeControllerMethod($route);
            $permissions = $this->group->permissions;
            if (in_array($routeMethod, $configRoutes ['generalAccess']) || in_array($routeMethod, array_flatten(array_only($configRoutes, array_keys(json_decode($permissions, '1'))))) || in_array($routeMethod, array_keys(json_decode($permissions, '1')))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Method used to split the controller and method name from routes
     *
     * @vendor Contus
     *
     * @package User
     * @return string
     */
    public function routeControllerMethod($route)
    {
        $split = explode('\\', $route);
        return $split [count($split) - 1];
    }

    /**
     * Method used to split the controller class alone from routes
     *
     * @vendor Contus
     *
     * @package User
     * @return string
     */
    public function routeController($route)
    {
        return explode('@', $route) [0];
    }
}