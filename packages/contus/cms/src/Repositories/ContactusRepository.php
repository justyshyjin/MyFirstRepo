<?php

/**
 * ContactUs Repository
 *
 * To manage the functionalities related to the ContactUs Controller
 *
 * @vendor Contus
 *
 * @package Cms
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Base\Helpers\StringLiterals;
use Contus\Cms\Models\StaticPages;
use Contus\Cms\Models\Contactus;

class ContactusRepository extends BaseRepository {

    /**
     * Class property to hold the key which hold the Contact us object
     *
     * @var object
     */
    protected $_contactUs;
    /**
     * Construct method
     *
     * @param Contus\Cms\Models\Contactus $contactUs
     */
    public function __construct(Contactus $contactUs) {
        parent::__construct ();
        $this->_contactUs = $contactUs;
        $this->setRules ( [ 'name' => 'required|max:100|min:3','email' => 'required|email','phone' => 'required|numeric|min:6','message' => 'required|max:255' ] );
    }
    /**
     * Store a newly created static content or update the static content.
     *
     * @param $id input
     * @return boolean
     */
    public function addContactus($id = null) {
        $this->_validate ();
        $contactus = new Contactus ();
        $contactus->is_active = 1;
        $contactus->creator_id = 1;
        $contactus->fill ( $this->request->all () );
        return ($contactus->save ()) ? 1 : 0;
    }
    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($contactusBuilder) {
        /*
         * updated the all user record only an superadmin user.
         */
        if ($this->authUser->id != 1) {
            $contactusBuilder->where ( 'id', $this->authUser->id )->orWhere ( 'parent_id', $this->authUser->id );
        }

        return $contactusBuilder;
    }

    /**
     * Store a newly created contact content or update the contact content.
     *
     * @param $id input
     * @return boolean
     */

    /**
     * Get contactus contents like(name,message)
     *
     * @param int $id
     * @return object
     */
    public function getContacts() {
        return $this->_contactUs->paginate ( 10 )->toArray ();
    }

    /**
     * This function used to get the recent contacts list
     */
    public function getContactslists() {
        return $this->_contactUs->orderBy ( 'id', 'DESC' )->take ( 8 )->get ();
    }
    /**
     * Delete one Contact using ID
     *
     * @param int $id
     * @return boolean
     */
    public function deleteStaticContent($id) {
        $data = $this->_contactUs->find ( $id );
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
     * @return Contus\User\Repositories\BaseRepository
     */
    public function prepareGrid() {
        $this->setGridModel ( $this->_contactUs );
        return $this;
    }
    /**
     * Function to apply filter for search of latestnews grid
     *
     * @param mixed $builderStatic
     * @return \Illuminate\Database\Eloquent\Builder $builderUsers The builder object of users grid.
     */
    protected function searchFilter($contactusStatic) {
        $searchcontactusRecordUsers = $this->request->has ( StringLiterals::SEARCHRECORD ) && is_array ( $this->request->input ( StringLiterals::SEARCHRECORD ) ) ? $this->request->input ( StringLiterals::SEARCHRECORD ) : [ ];
        /**
         * Loop the search fields of users grid and use them to filter search results.
         */

        foreach ( $searchcontactusRecordUsers as $key => $value ) {
            if ($key == StringLiterals::ISACTIVE && $value == 'all') {
                continue;
            }

            $contactusStatic = $contactusStatic->where ( $key, 'like', "%$value%" );
        }

        return $contactusStatic;
    }
    /**
     * Get headings for grid
     *
     * @return array
     */
    public function getGridHeadings() {
        return [ StringLiterals::GRIDHEADING => [ [ 'name' => trans ( 'cms::testimonial.customer_name' ),StringLiterals::VALUE => 'name','sort' => false ],

        [ 'name' => trans ( 'cms::testimonial.phone' ),StringLiterals::VALUE => '','sort' => false ],[ 'name' => trans ( 'cms::testimonial.customer_email' ),StringLiterals::VALUE => '','sort' => false ],[ 'name' => trans ( 'cms::testimonial.customer_message' ),StringLiterals::VALUE => '','sort' => false ],[ 'name' => trans ( 'cms::staticcontent.created_at' ),StringLiterals::VALUE => '','sort' => false ],[ 'name' => trans ( 'cms::smstemplate.action' ),StringLiterals::VALUE => '','sort' => false ] ] ];
    }

    /**
     * This function used to get the rules
     */
    public function getStaticcontentRules() {
        return array(
                'rules' => $this->getRules(),
        );
    }

    /**
     * This function used to get the contact details for individual users
     */
    public function getContactInfo($id=''){
        return $this->_contactUs->select ( [ 'id','name','phone','email','message' ] )->find ( $id );
    }
}