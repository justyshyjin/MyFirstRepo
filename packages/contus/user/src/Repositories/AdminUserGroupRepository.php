<?php
/**
 * Admin UserGroup Repository
 *
 * To manage the functionalities related to the Admin UserGroup module from Admin UserGroup Controller
 * @name       AdminUserGroupRepository
 * @vendor Contus
 * @package User
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\User\Repositories;

use Contus\User\Contracts\IAdminUserGroupRepository;
use Contus\User\Models\User;
use Contus\Base\Repository as BaseRepository;
use Contus\User\Models\UserGroup;
use Illuminate\Support\Facades\Hash;
use Contus\Base\Helpers\StringLiterals;

class AdminUserGroupRepository extends BaseRepository implements IAdminUserGroupRepository {

    /**
     * Construct method
     *
     * @vendor Contus
     * @package User
     * @param Contus\User\Models\UserGroup $userGroup
     * @param Contus\User\Models\User $user
     */
    public function __construct(UserGroup $userGroup, User $user) {
        parent::__construct ();
        $this->_userGroup       = $userGroup;
        $this->_user            = $user;
        $this->setRules([
            'name'          =>  'required|alpha|unique:user_groups|max:50',
            'permissions'   =>  'required',
        ]);
    }

    /**
     * Fetch user group to display in admin block.
     *
     * @vendor Contus
     * @package User
     * @return response
     */
    public function getUserGroups($status) {
      return $this->_userGroup->where('id', '!=', 1)->paginate (10);
    }
    /**
     * Fetch all user group to display in admin block.
     *
     * @vendor Contus
     * @package User
     * @return response
     */
    public function getAllUserGroups() {
        return $this->_userGroup->pluck ( 'name', 'id' );
    }
    /**
     * Get headings for grid
     *
     * @vendor Contus
     * @package User
     * @return array
     */
    public function getGridHeadings() {
        return [
            StringLiterals::GRIDHEADING => [
                ['name'=> 'Group Name', StringLiterals::VALUE => 'name', 'sort' => true],
                ['name'=> trans('user::user.action'),StringLiterals::VALUE=> '','sort' => false]
            ]
        ];
    }
    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     *
     * @vendor Contus
     * @package User
     * @return Contus\User\Repositories\BaseRepository
     */
    public function prepareGrid() {
        $this->setGridModel($this->_userGroup)->setEagerLoadingModels ( [ 'users' ] );
        return $this;
    }

    /**
     * Store a newly created admin group.
     * Converts the permissions array to json format and saves in db.
     *
     * @param $id  input values
     *
     * @return response
     */
    public function addOrUpdateGroups( $id = null) {
        if(!empty($id)) {
            $userGroup = $this->_userGroup->find($id);
            $this->setRule('name', 'required|alpha|unique:user_groups,name,'.$userGroup->id.'|max:50');
        } else {
            $userGroup = $this->_userGroup;
        }

        $this->validate($this->request, $this->getRules());

        $userGroup->fill($this->request->except('_token'));
        $userGroup->permissions = json_encode(array_fill_keys($this->request->permissions, (int)1));
        $userGroup->save();
        return true;
    }
    
    /**
     * Fetch group to edit.
     *
     * @return response
     */
    public function getUserGroup($id) {
        return $this->_userGroup->find($id);
    }
    /**
     * Check the group name provied is unique group name.
     * check only if the request has the expected param
     *
     * @param int $id 
     * @return boolean
     */
    public function isUniqueGroupName($id = null){
        return $this->isUniqueRequestValue($this->_userGroup,'name',$id);
    }

    /**
     * Function to apply filter for search of user groups grid
     *
     * @vendor Contus
     * @package User
     * @param mixed $builderUserGroups
     * @return \Illuminate\Database\Eloquent\Builder $builderUserGroups The builder object of user groups grid.
     */
    protected function searchFilter($builderUserGroups) {
        $searchRecordUserGroups = $this->request->has(StringLiterals::SEARCHRECORD) && is_array($this->request->input(StringLiterals::SEARCHRECORD)) ? $this->request->input(StringLiterals::SEARCHRECORD) : [];
        
        /**
         * Loop the search fields of user groups grid and use them to filter search results.
         */
        foreach($searchRecordUserGroups as $key=>$value) {
         if($key == StringLiterals::ISACTIVE && $value == 'all'){
            continue;
         } 

         $builderUserGroups = $builderUserGroups->where($key,'like',"%$value%");
        }
    
        return $builderUserGroups;
    }
}