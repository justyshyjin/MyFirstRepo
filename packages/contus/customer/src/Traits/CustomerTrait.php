<?php

/**
 * CustomerTrait
 *
 * To manage the functionalities related to the Categories module from Categories Controller
 *
 * @vendor Contus
 *
 * @package customer
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Customer\Traits;
use Carbon\Carbon;
use Contus\Video\Models\Collection;

trait CustomerTrait {
    /**
     * Fetch all soft deleted customers
     *
     * @vendor Contus
     *
     * @package Customer
     * @return object
     */
    public function findSoftDeletedUser() {
        return $this->_customer->onlyTrashed ();
    }
    /**
     * Fetch all the customers
     *
     * @vendor Contus
     *
     * @package Customer
     * @return array
     */
    public function getAllCustomers() {
        return $this->_customer->paginate ( 10 )->toArray ();
    }
    /**
     * Fetches one customer
     *
     * @vendor Contus
     *
     * @package Customer
     * @param int $customerId
     * @return object
     */
    public function getCustomer($customerId) {
        return $this->_customer->find ( $customerId );
    }
    /**
     * Get the profile picture and name for profilesettings
     */
    public function getProfile() {
        return $this->_customer->where ( 'id', $this->authUser->id )->select ( 'name', 'profile_picture' )->first ();
    }
    /**
     * delete customer.
     *
     * @vendor Contus
     *
     * @package Customer
     * @param $customerId input
     * @return boolean
     */
    public function deleteCustomer($customerId) {
        $data = $this->_customer->find ( $customerId );
        if ($data) {
            $data->delete ();
            return true;
        } else {
            return false;
        }
    }
    /**
     * Prepare the grid
     * set the grid model and relation model to be loaded
     *
     * @vendor Contus
     *
     * @package Customer
     * @return Contus\User\Repositories\BaseRepository
     */
    public function prepareGrid() {
        $this->setGridModel ( $this->_customer )->setEagerLoadingModels ( [ 'exams' ,'activeSubscriber'] );
        return $this;
    }
    /**
     * Function to apply filter for search of customers grid
     *
     * @vendor Contus
     *
     * @package Customer
     * @param mixed $builderVideos
     * @return \Illuminate\Database\Eloquent\Builder $builderVideos The builder object of videos grid.
     */
    protected function searchFilter($builder) {
        $searchRecordVideos = $this->request->has ( 'searchRecord' ) && is_array ( $this->request->input ( 'searchRecord' ) ) ? $this->request->input ( 'searchRecord' ) : [ ];
        $name = $email = $is_active = $phone = $subscriber = $filter_startdate = $filter_enddate = null;
        extract ( $searchRecordVideos );

        if ($name) {
            $builder = $builder->where ( 'name', 'like', '%' . $name . '%' );
        }
        if ($email) {
            $builder = $builder->where ( 'email', 'like', '%' . $email . '%' );
        }
        if ($phone) {
            $builder = $builder->where ( 'phone', 'like', '%' . $phone . '%' );
        }
        if ($subscriber) {
            $builder = $builder->wherehas('activeSubscriber',function($query) use ($subscriber){
              $query->where('subscription_plan_id', $subscriber);
            });
        }
        if($filter_startdate && $filter_enddate){
          $builder = $builder->wherehas('activeSubscriber',function($query) use ($filter_startdate,$filter_enddate){
            $query->where('start_date','>=',Carbon::createFromFormat ( 'd-m-Y', $filter_startdate ))->where('end_date','<=',Carbon::createFromFormat ( 'd-m-Y', $filter_enddate ));
          });
        }else if($filter_startdate){
          $dateEnd = new Carbon();
          $builder = $builder->wherehas('activeSubscriber',function($query) use ($filter_startdate,$dateEnd){
            $query->whereBetween('start_date', [ Carbon::createFromFormat ( 'd-m-Y', $filter_startdate )->format ( 'Y-m-d' ), $dateEnd->format('Y-m-d')]);
          });
        }
        else if($filter_enddate){
          $dateStart = new Carbon();
          $dateStart = $dateStart->subYear();
          $builder = $builder->wherehas('activeSubscriber',function($query) use ($dateStart,$filter_enddate){
            $query->whereBetween('end_date', [  $dateStart->format('Y-m-d'),Carbon::createFromFormat ( 'd-m-Y', $filter_enddate )->format ( 'Y-m-d' )]);
          });
        }
        if (is_numeric ( $is_active )) {
            $builder = $builder->where ( 'is_active', $is_active );
        }
        return $builder;
    }
    /**
     * Get headings for grid
     *
     * @vendor Contus
     *
     * @package Collection
     * @return array
     */
    public function getGridHeadings() {
       return [ 'heading' => [ [ 'name' => 'Name','value' => 'name','sort' => true ],[ 'name' => 'Email','value' => 'email','sort' => true ],[ 'name' => 'Phone','value' => 'phone','sort' => false ],[ 'name' => 'Created Date','value' => 'created_at','sort' => false ],[ 'name' => 'subscription plan','value' => 'phone','sort' => false ],[ 'name' => 'Start Date','value' => 'phone','sort' => false ],[ 'name' => 'End Date','value' => 'phone','sort' => false ],[ 'name' => 'Status','value' => 'is_active','sort' => false ],[ 'name' => 'Action','value' => '','sort' => false ] ] ];
    }
    /**
     * Function to add exams for empty exam users
     *
     * @param object $user
     * @return boolean
     */
    public function checkAndAddExams($user) {
     $exams = $user->exams()->where ( 'is_active', 1 )->pluck ( 'collections.id' )->toArray ();
     if (! $exams) {
      $exams = Collection::where ( 'is_active', 1 )->pluck ( 'id' )->toArray ();
      $user->exams ()->attach ( $exams );
     }
     return true;
    }
    /**
     * Function to login Already registerd Users with social login
     *
     * @param object $user
     * @param array $userDetails
     * @return object
     */
    private function loginSocialRegisteredUsers($user, $userDetails) {
     if (empty ( $user->login_type )) {
      $user->login_type = $userDetails ['login_type'];
     }
     $user->access_token = $this->randomCharGen ( 30 );
     if (isset ( $userDetails ['device_type'] )) {
      $user->device_type = $userDetails ['device_type'];
     }
     if (isset ( $userDetails ['device_token'] )) {
      $user->device_token = $userDetails ['device_token'];
     }
     $user->profile_picture = ($user->profile_picture) ? $user->profile_picture : $userDetails ['profile_picture'];
     $user->name = ($user->name) ? $user->name : $userDetails ['name'];
     $user->save ();
     return $user;
    }
    
    /**
     * This Method used to generate random char based on the count.
     *
     * @return boolean
     */
    public function randomCharGen($count, $upperCase = false)
    {
     $randomCharacters = substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789", $count)), 0, $count);
     return ($upperCase) ? strtoupper($randomCharacters) : $randomCharacters;
    }
    
}
