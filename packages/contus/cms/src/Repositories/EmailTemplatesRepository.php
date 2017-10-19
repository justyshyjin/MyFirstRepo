<?php

/**
 * Email Templates Repository
 * To manage the functionalities related to the Customer module from Email Templates Controller
 *
 * @name EmailTemplatesRepository
 * @vendor Contus
 * @package Cms
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Repositories;

use Contus\Base\Repository as BaseRepository;
use Contus\Cms\Models\EmailTemplates;
use Contus\Base\Helpers\StringLiterals;

class EmailTemplatesRepository extends BaseRepository {

    /**
     * Class property to hold the key which hold the email template object
     *
     * @var object
     */
    protected $_emailTemplates;
    /**
     * Construct method
     *
     * @vendor Contus
     *
     * @package Cms
     *
     * @param Contus\Cms\Models\EmailTemplates $emailTemplates
     */
    public function __construct(EmailTemplates $emailTemplates) {
        parent::__construct ();
        $this->_emailTemplates = $emailTemplates;
    }
    /**
     * Store a newly created email template or update the email template.
     * @vendor Contus
     *
     * @package Cms
     * @param $id input
     * @return boolean
     */
    public function addOrUpdateEmailTemplates($id = null) {
        if (! empty ( $id )) {
            $emailTemplates = $this->_emailTemplates->find ( $id );
            if (! is_object ( $emailTemplates )) {
                return false;
            }
            $this->setRules ( [ 'name' => 'sometimes|required','is_active' => 'sometimes|required|boolean','subject' => 'sometimes|required','content' => 'sometimes|required' ] );
            $emailTemplates->updator_id = $this->authUser->id;
        } else {
            $this->setRules ( [ 'name' => 'required|max:255','subject' => 'required','content' => 'required' ] );
            $emailTemplates = new EmailTemplates ();
            $emailTemplates->is_active = 1;
            $emailTemplates->creator_id = $this->authUser->id;
        }
        $this->_validate ();
        $emailTemplates->fill ( $this->request->except ( '_token' ) );
        return ($emailTemplates->save ()) ? 1 : 0;
    }
    /**
     * Get one email template using id
     * @vendor Contus
     *
     * @package Cms
     * @param int $id
     * @return object
     */
    public function getEmailTemplates($id) {
        return $this->_emailTemplates->select ( [ 'id','name','slug','subject','content','is_active' ] )->find ( $id );
    }
    /**
     * Get all email templates
     *
     * @vendor Contus
     *
     * @package Cms
     * @return array
     */
    public function getAllEmailTemplates() {
        return $this->_emailTemplates->select ( [ 'id','name','slug','subject','content','is_active' ] )->paginate ( 10 )->toArray ();
    }
    /**
     * Delete one Email template using ID
     * @vendor Contus
     *
     * @package Cms
     * @param int $id
     * @return boolean
     */
    public function deleteEmailTemplate($id) {
        $data = $this->_emailTemplates->find ( $id );
        if ($data) {
            $data->delete ();
            return true;
        } else {
            return false;
        }
    }
    public function fetchEmailTemplate($slug) {
        return $this->_emailTemplates->where ( 'slug', $slug )->first ();
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
        $this->setGridModel ( $this->_emailTemplates );
        return $this;
    }

    /**
     * update grid records collection query
     *
     * @param mixed $builder
     * @return mixed
     */
    protected function updateGridQuery($emailBuilder) {
        /*
         * updated the all user record only an superadmin user.
         */
        if ($this->authUser->id != 1) {
            $emailBuilder->where ( 'id', $this->authUser->id )->orWhere ( 'parent_id', $this->authUser->id );
        }

        return $emailBuilder;
    }

    /**
     * Function to apply filter for search of latestnews grid
     * @vendor Contus
     *
     * @package Cms
     * @param mixed $builderUsers
     * @return \Illuminate\Database\Eloquent\Builder $builderUsers The builder object of users grid.
     */
    protected function searchFilter($builderEmail) {
        $searchRecordUsers = $this->request->has ( StringLiterals::SEARCHRECORD ) && is_array ( $this->request->input ( StringLiterals::SEARCHRECORD ) ) ? $this->request->input ( StringLiterals::SEARCHRECORD ) : [ ];
        /**
         * Loop the search fields of users grid and use them to filter search results.
         */

        foreach ( $searchRecordUsers as $key => $value ) {
            if ($key == StringLiterals::ISACTIVE && $value == 'all') {
                continue;
            }

            $builderEmail = $builderEmail->where ( $key, 'like', "%$value%" );
        }

        return $builderEmail;
    }
    /**
     * Get headings for grid
     * @vendor Contus
     *
     * @package Cms
     * @return array
     */
    public function getGridHeadings() {
        return [ StringLiterals::GRIDHEADING => [ [ 'name' => trans ( 'cms::emailtemplate.name' ),StringLiterals::VALUE => 'name','sort' => false ],[ 'name' => trans ( 'cms::emailtemplate.subject' ),StringLiterals::VALUE => '','sort' => false ],
        [ 'name' => trans ( 'cms::emailtemplate.created_at' ),StringLiterals::VALUE => '','sort' => false ],[ 'name' => trans ( 'cms::emailtemplate.action' ),StringLiterals::VALUE => '','sort' => false ] ] ];
    }
}