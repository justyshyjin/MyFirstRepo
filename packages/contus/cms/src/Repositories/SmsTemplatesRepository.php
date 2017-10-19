<?php

/**
 * Sms Templates Repository
 * To manage the functionalities related to the Customer module from Sms Templates Controller
 *
 * @name SmsTemplatesRepository
 * @vendor Contus
 * @package Cms
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Cms\Models\SmsTemplates;
use Contus\Base\Helpers\StringLiterals;

class SmsTemplatesRepository extends BaseRepository {

    /**
     * Class property to hold the key which hold the sms template object
     *
     * @var object
     */
    protected $_smsTemplates;
    /**
     * Construct method
     * @vendor Contus
     *
     * @package Cms
     * @param Contus\Cms\Models\SmsTemplates $smsTemplates
     */
    public function __construct(SmsTemplates $smsTemplates) {
        parent::__construct ();
        $this->_smsTemplates = $smsTemplates;
        $this->setRules ( [ 'name' => 'sometimes|required','is_active' => 'sometimes|required|boolean','content' => 'sometimes|required' ] );
    }
    /**
     * Store a newly created sms template or update the sms template.
     * @vendor Contus
     *
     * @package Cms
     * @param $id input
     * @return boolean
     */
    public function addOrUpdateSmsTemplates($id = null) {
        if (! empty ( $id )) {
            $smsTemplates = $this->_smsTemplates->find ( $id );
            if (! is_object ( $smsTemplates )) {
                return false;
            }
            $this->setRules ( [ 'name' => 'sometimes|required','is_active' => 'sometimes|required|boolean','content' => 'sometimes|required' ] );
            $smsTemplates->updator_id = $this->authUser->id;
        } else {
            $this->setRules ( [ 'name' => 'required|max:255','content' => 'required' ] );
            $smsTemplates = new SmsTemplates ();
            $smsTemplates->is_active = 1;
            $smsTemplates->creator_id = $this->authUser->id;
        }
        $this->_validate ();
        $smsTemplates->fill ( $this->request->except ( '_token' ) );
        return ($smsTemplates->save ()) ? 1 : 0;
    }
    /**
     * Get one sms template using id
     * @vendor Contus
     *
     * @package Cms
     * @param int $id
     * @return object
     */
    public function getSmsTemplates($id) {
        return $this->_smsTemplates->find ( $id );
    }
    /**
     * Get all sms templates
     * @vendor Contus
     *
     * @package Cms
     * @return array
     */
    public function getAllSmsTemplates() {
        return $this->_smsTemplates->paginate ( 10 )->toArray ();
    }
    /**
     * Delete one Sms template using ID
     * @vendor Contus
     *
     * @package Cms
     * @param int $id
     * @return boolean
     */
    public function deleteSmsTemplate($id) {
        $data = $this->_smsTemplates->find ( $id );
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
     * @vendor Contus
     *
     * @package Cms
     * @return Contus\User\Repositories\BaseRepository
     */
    public function prepareGrid() {
        $this->setGridModel ( $this->_smsTemplates );
        return $this;
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($smsBuilder) {
        /*
         * updated the all user record only an superadmin user.
         */
        if ($this->authUser->id != 1) {
            $smsBuilder->where ( 'id', $this->authUser->id )->orWhere ( 'parent_id', $this->authUser->id );
        }

        return $smsBuilder;
    }

    /**
     * Function to apply filter for search of latestnews grid
     * @vendor Contus
     *
     * @package Cms
     * @param mixed $builderUsers
     * @return \Illuminate\Database\Eloquent\Builder $builderUsers The builder object of users grid.
     */
    protected function searchFilter($builderSms) {
        $searchRecordUsers = $this->request->has ( StringLiterals::SEARCHRECORD ) && is_array ( $this->request->input ( StringLiterals::SEARCHRECORD ) ) ? $this->request->input ( StringLiterals::SEARCHRECORD ) : [ ];
        /**
         * Loop the search fields of users grid and use them to filter search results.
         */

        foreach ( $searchRecordUsers as $key => $value ) {
            if ($key == StringLiterals::ISACTIVE && $value == 'all') {
                continue;
            }

            $builderSms = $builderSms->where ( $key, 'like', "%$value%" );
        }

        return $builderSms;
    }
    /**
     * Get headings for grid
     * @vendor Contus
     *
     * @package Cms
     * @return array
     */
    public function getGridHeadings() {
        return [ StringLiterals::GRIDHEADING => [ [ 'name' => trans ( 'cms::smstemplate.name' ),StringLiterals::VALUE => 'name','sort' => false ],

        [ 'name' => trans ( 'cms::smstemplate.content' ),StringLiterals::VALUE => '','sort' => false ],[ 'name' => trans ( 'cms::smstemplate.created_at' ),StringLiterals::VALUE => '','sort' => false ],[ 'name' => trans ( 'cms::latestnews.status' ),StringLiterals::VALUE => 'is_active','sort' => false ],[ 'name' => trans ( 'cms::smstemplate.action' ),StringLiterals::VALUE => '','sort' => false ] ] ];
    }
}