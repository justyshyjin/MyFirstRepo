<?php

/**
 * StaticContent Controller
 * To manage the functionalities related to the static content gird api methods
 *
 * @vendor Contus
 * @package cms
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Cms\Api\Controllers\Staticcontent;

use Contus\Base\ApiController;
use Contus\Cms\Repositories\staticcontentsRepository;
use Contus\Base\Helpers\StringLiterals;
use Contus\Cms\Repositories\StaticContentRepository;
use Contus\Cms\Repositories\ContactusRepository;

class StaticContentController extends ApiController {
    /**
     * class property to hold the instance of staticcontentsRepository
     *
     * @var \Contus\Base\Repositories\staticcontentsRepository
     */
    public $staticContentRepository;
    /**
     * Construct method
     */
    public function __construct(StaticContentRepository $staticContentRepository,ContactusRepository $contactUsrepository) {
        parent::__construct ();
        $this->repository = $staticContentRepository;
        $this->contactus = $contactUsrepository;
    }

    /**
     * To get the Static content infomations based on slug.
     * @param $getSubscriptionSlug
     * @return json
     */
    public function getStaticContent($getSubscriptionSlug) {
        $data = $this->repository->getStaticcontentSlug ($getSubscriptionSlug);
        return ($data) ? $this->getSuccessJsonResponse ( [ 'response' => $data ] ) : $this->getErrorJsonResponse ( [ ], 422);
    }
    /**
     * To get the Static content Rules
     * @return json
     */
    public function getStaticContentRules() {
        $data = $this->contactus->getStaticcontentRules ();
        return ($data) ? $this->getSuccessJsonResponse ( [ 'response' => $data ] ) : $this->getErrorJsonResponse ( [ ], 422);
    }
    /**
     * function to send api to mobile with contact informations
     * @return json
     */
    public function getSiteAddress(){
        return $this->getSuccessJsonResponse ( [ 'response' => ['email'=>config ()->get ( 'settings.general-settings.site-settings.site_email_id' ),'phone'=>config ()->get ( 'settings.general-settings.site-settings.site_mobile_number' ),'address'=>config ()->get ( 'settings.general-settings.site-settings.site_local_address' )] ] );
    }

}
