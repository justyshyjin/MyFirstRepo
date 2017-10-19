<?php

/**
 * Admin User Repository
 *
 * To manage the functionalities related to the Admin User module from Admin User Controller
 *
 * @name AdminUserRepository
 * @vendor Contus
 * @package User
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\User\Repositories;

use Contus\User\Contracts\IAdminUserRepository;
use Contus\User\Models\User;
use Contus\Video\Repositories\AWSUploadRepository;
use Contus\Base\Repository as BaseRepository;
use Contus\Base\Repositories\UploadRepository;
use Contus\User\Models\UserGroup;
use Illuminate\Support\Facades\Hash;
use Contus\Base\Helpers\StringLiterals;
use Contus\Cms\Repositories\EmailTemplatesRepository;
use Contus\Notification\Repositories\NotificationRepository;

class AdminUserRepository extends BaseRepository implements IAdminUserRepository
{
    /**
     * Class property to hold the key which hold the group name requested
     *
     * @var string
     */
    protected $requestedUserEmail = 'q';
    /**
     * Class property to hold the upload repository object
     *
     * @var Contus\Base\Repositories\UploadRepository
     */
    protected $uploadRepository = null;
    /**
     * Class property to hold the key which hold the admin group object
     *
     * @var object
     */
    protected $_adminGroup;
    /**
     * Class property to hold the key which hold the user object
     *
     * @var object
     */
    protected $_user;

    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package User
     * @param Contus\User\Models\UserGroup $userGroup
     * @param Contus\User\Models\User $user
     * @param Contus\Base\Repositories\UploadRepository $uploadRepository
     */
    public function __construct(UserGroup $userGroup, User $user, EmailTemplatesRepository $emailTemplates, UploadRepository $uploadRepository, NotificationRepository $notification, AWSUploadRepository $awsRepository)
    {
        parent::__construct();
        $this->_userGroup = $userGroup;
        $this->_user = $user;
        $this->email = $emailTemplates;
        $this->uploadRepository = $uploadRepository;
        $this->notification = $notification;
        $this->setRules(['name' => StringLiterals::REQUIRED,'gender' => StringLiterals::REQUIRED,StringLiterals::EMAIL => 'required|email|unique:users', 'phone' => 'required|numeric|min:10', 'user_group_id' => 'required']);
        $this->awsRepo = $awsRepository;
    }

    /**
     * Store a newly created admin user.
     *
     * @param $id input
     * values
     *
     * @vendor Contus
     * @package User
     * @return boolean
     */
    public function addOrUpdateUsers($id = null)
    {
       $this->uploadRepository->defineRepositoryFileRule($this);
        if (app()->make('request')->has(StringLiterals::PROFILE)) {
            $this->uploadRepository->setModelIdentifier(UploadRepository::MODEL_IDENTIFIER_PROFILE)->setRequestParamKey(StringLiterals::PROFILE)->setConfig();
        }
        if (!empty ($id)) {
            $adminUser = $this->_user->find($id);
            $this->setRule(StringLiterals::EMAIL, 'required|email|unique:users,email,' . $adminUser->id);
        } else {
            $adminUser = new User ();
            $adminUser->password = Hash::make("admin123");
            $adminUser->access_token = $this->randomCharGen(30);
            $adminUser->parent_id = $this->authUser->id;
        }
        $this->validate(app()->make('request'), $this->getRules());
        if (!empty($id)) {
            $adminUser->profile_image = app()->make('request')->profile_image;
        }
        $adminUser->fill(app()->make('request')->except('_token'));
        if ($adminUser->save()) {
            if (empty($id)) {
                $this->email = $this->email->fetchEmailTemplate('new_user');
                $this->email->content = str_replace(['##NAME##', '##EMAIL##', '##PASSWORD##'], [$adminUser->name, $adminUser->email, 'admin123'], $this->email->content);
            } else {
                $this->email = $this->email->fetchEmailTemplate('update_user');
                $this->email->content = str_replace(['##NAME##'], [$adminUser->name], $this->email->content);
            }
            $this->notification->email ( $adminUser, $this->email->subject, $this->email->content );
            return true;
        }
       
    }

    /**
     * Fetch users to display in admin block.
     *
     * @vendor Contus
     *
     * @package User
     * @return response
     */
    public function getUsers($status)
    {
        $users = $this->_user;
        $users->filter($status);
        return $users->paginate(10);
    }

    /**
     * Fetch user to edit.
     *
     * @vendor Contus
     *
     * @package User
     * @return response
     */
    public function getUser($id)
    {
        return $this->_user->find($id);
    }

    /**
     * List groups
     *
     * @vendor Contus
     *
     * @package User
     * @return response
     */
    public function getGroupsList()
    {
        return $this->_adminGroup->lists('name', 'id');
    }

    /**
     * Method used to update password.
     * Set validation rule for change password.
     *
     * Check the old password with logged in user password and update the password.
     *
     * @vendor Contus
     *
     * @package User
     * @return boolean
     */
    public function changePassword()
    {
        $adminUser = $this->_user->find($this->authUser->id);
        if (Hash::check($this->request->old_password, $adminUser->password)) {
            $adminUser->password = Hash::make($this->request->password);
            $adminUser->save();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method used to update profile information
     *
     * @vendor Contus
     *
     * @package User
     * @param $id input
     * values
     *
     * @return boolean
     */
    public function updateProfile($id = null)
    {
        $this->uploadRepository->defineRepositoryFileRule($this);
        if (!empty ($id)) {
            $adminUser = $this->_user->find($id);
            $this->setRule(StringLiterals::EMAIL, 'required|email|unique:admin_users,email,' . $adminUser->id);
            $this->removeRule('user_groups');
        }
        $this->validate($this->request, $this->getRules());
        $adminUser->fill($this->request->except('_token'));
        if ($adminUser->save() && $this->request->has('uploadedImage')) {
            $this->uploadRepository->handleUpload($adminUser);
        }
        return true;
    }

    /**
     * Check the user email provied is unique user email.
     * check only if the request has the expected param
     *
     * @vendor Contus
     *
     * @package User
     * @param int $id
     * @return boolean
     */
    public function isUniqueUserEmail($id = null)
    {
        if ($this->request->has($this->requestedUserEmail)) {
            $adminUserQuery = $this->_user->where(StringLiterals::EMAIL, $this->request->get($this->requestedUserEmail));
            if ($id) {
                $adminUserQuery->where('id', '!=', $id);
            }

            return $adminUserQuery->count() == 0;
        }
        return false;
    }

    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     *
     * @vendor Contus
     *
     * @package User
     * @return Contus\User\Repositories\BaseRepository
     */
    public function prepareGrid()
    {
        $this->setGridModel($this->_user)->setEagerLoadingModels(['group']);
        return $this;
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($builder)
    {
        /*
         * updated the all user record only an superadmin user.
         */
        if ($this->authUser->id != 1) {
            $builder->where('id', $this->authUser->id)->orWhere('parent_id', $this->authUser->id);
        }
        return $builder;
    }

    /**
     * Function to apply filter for search of users grid
     *
     * @vendor Contus
     *
     * @package User
     * @param mixed $builderUsers
     * @return \Illuminate\Database\Eloquent\Builder $builderUsers The builder object of users grid.
     */
    protected function searchFilter($builderUsers)
    {
        $searchRecordUsers = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        /**
         * Loop the search fields of users grid and use them to filter search results.
         */
        foreach ($searchRecordUsers as $key => $value) {
            if ($value != 'all') {
                $builderUsers = $builderUsers->where($key, 'like', "%$value%");
            }
        }

        return $builderUsers;
    }

    /**
     * Get headings for grid
     *
     * @vendor Contus
     *
     * @package User
     * @return array
     */
    public function getGridHeadings()
    {
        return [StringLiterals::GRIDHEADING => [['name' => trans('user::user.username'), StringLiterals::VALUE => 'name', 'sort' => true], ['name' => trans('user::user.email'), StringLiterals::VALUE => StringLiterals::EMAIL, 'sort' => true], ['name' => trans('user::user.user_group'), StringLiterals::VALUE => '', 'sort' => false], ['name' => trans('user::user.status'), StringLiterals::VALUE => 'is_active', 'sort' => false], ['name' => trans('user::user.action'), StringLiterals::VALUE => '', 'sort' => false]]];
    }

    /**
     * Repository function to delete profile image of a user.
     *
     * @param integer $id
     * The id of the user.
     * @return boolean True if the profile image is deleted and false if not.
     */
    public function deleteProfileImage($id)
    {
        /**
         * Check if user id exists.
         */
        if (!empty ($id)) {
            $user = $this->_user->findorfail($id);
            /**
             * Delete the profile image using the profile image path field from the database.
             */
            if (isset($user->profile_image) && $user->profile_image !== '') {
                $explodedProfileImage = array();
                $ProfileImage = $user->profile_image;
                $explodedProfileImage = explode('/', $ProfileImage);

                $this->awsRepo->deleteProfileImage_s3butcket($user->profile_image);

                /**
                 * Empty the profile_image and profile_image_path field in the database.
                 */

                $user->profile_image = '';
                $user->profile_image_path = '';
                $user->save();
                $deleteStatus = true;
            } else {
                $deleteStatus = false;
            }
            return $deleteStatus;
        } else {
            return false;
        }
    }
}