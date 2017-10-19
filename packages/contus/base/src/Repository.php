<?php

/**
 * Base Repository
 *
 * @name Repository
 * @vendor Contus
 * @package Base
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

namespace Contus\Base;

use BadMethodCallException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Contus\Base\Model;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Auth\Authenticatable;
use Contus\Base\Handlers\ValidationHandler;
use Contus\Base\Handlers\GridHandler;
use Contus\Base\Contracts\GridableRepository;
use Contus\Base\Helpers\StringLiterals;
use Contus\Customer\Models\Customer;
use Contus\Customer\Models\MypreferencesVideo;
use Contus\Video\Models\Collection;

abstract class Repository implements GridableRepository
{
    use ValidatesRequests, ValidationHandler, GridHandler;
    /**
     * The request registered on Base Repository.
     *
     * @var object
     */
    protected $request;
    /**
     * The authenticated user model.
     *
     * @var object
     */
    protected $authUser = null;
    /**
     * The class property to hold the logger object
     *
     * @var object
     */
    protected $logger;
    /**
     * @vendor Contus
     * Class constants for holding various request type handled repositories
     */
    const REQUEST_TYPE_API = 'API';
    const REQUEST_TYPE_HTTP = 'HTTP';
    /**
     * Class property for holding various request type handled repositories
     *
     * @var array
     */
    protected $requestTypes = [self::REQUEST_TYPE_HTTP, self::REQUEST_TYPE_API];
    /**
     * Class property to hold the request type
     *
     * @var string
     */
    protected $requestType = self::REQUEST_TYPE_HTTP;
    /**
     * Class property holding instance of the DatabaseManager
     *
     * @var \Illuminate\Database\DatabaseManager
     */
    protected $db = null;

    /**
     * Class contructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->request = app()->make('request');
        $this->logger = app()->make('log');
        $this->db = app()->make('db');

        if ($authUser = app()->make('auth')->user()) {
            $this->authUser = $authUser;
        }
    }

    /**
     * Create the response for when a request fails validation.
     *
     * @param \Illuminate\Http\Request $request
     * @param array $errors
     * @return \Illuminate\Http\Response
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {

        if ($request->ajax() || $request->wantsJson() || $this->requestType == static::REQUEST_TYPE_API) {
            return new JsonResponse (['error' => true, 'statusCode' => 422, 'message' => (($this->request->header('x-request-type') == 'mobile') ? array_shift($errors) [0] : $errors)], 422);
        }

        return redirect()->to($this->getRedirectUrl())->withInput($request->input())->withErrors($errors, $this->errorBag());
    }

    /**
     * Get the property name through method name
     *
     * @param string $methodName
     * @return string
     *
     */
    private function getExpectedPropertyName($methodName)
    {
        return lcfirst(substr($methodName, 3));
    }

    /**
     * Magic Method helps to define and get the class property with actual methods
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     *
     * @throws BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        $classProperty = $this->getExpectedPropertyName($method);

        if (!property_exists($this, $classProperty)) {
            throw new BadMethodCallException ("Method [$method] does not exist.");
        }

        switch (substr($method, 0, 3)) {
            case 'get' :
                return $this->{$classProperty};
            case 'set' :
                $propertyValue = array_shift($parameters);
                $this->{$classProperty} = $propertyValue;
                break;
            default :
                throw new BadMethodCallException ("Method [$method] does not exist.");
        }

        return $this;
    }

    /**
     * Throw new Http response as exception with json.
     * uses HttpResponseException
     *
     * @param boolean $includeFlash
     * @param int $statusCode
     * @param int $statusCode
     * @return void
     *
     * @throws \Illuminate\Http\Exception\HttpResponseException
     */
    protected function throwJsonResponse($includeFlash = false, $statusCode = 404, $message = null)
    {
        $message = is_null($message) ? trans('base::general.resource_not_exist') : $message;

        if ($includeFlash) {
            $this->request->session()->flash(StringLiterals::ERROR, $message);
        }

        throw new HttpResponseException (new JsonResponse ([StringLiterals::ERROR => true, 'statusCode' => $statusCode, 'status' => StringLiterals::ERROR, 'messages' => $message], $statusCode));
    }

    /**
     * Throw new Http response as exception.
     * uses NotFoundHttpException
     *
     * @param boolean $includeFlash
     * @param int $statusCode
     * @param int $statusCode
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function throwResponse($includeFlash = false, $statusCode = 404, $message = null)
    {
        $message = is_null($message) ? trans('base::general.resource_not_exist') : $message;

        if ($includeFlash) {
            $this->request->session()->flash(StringLiterals::ERROR, $message);
        }

        abort(404, $message);
    }

    /**
     * Get logged user id
     * if the there is no active session 0 is return
     *
     * @return int
     */
    protected function getLoggedUserId()
    {
        return ($this->authUser instanceof Authenticatable) ? $this->authUser->id : 1;
    }

    /**
     * get various configuration by model
     *
     * @param string $model
     * @return mixed (object | null)
     */
    public function getFileConfigurationByModel($model)
    {
        $config = config("settings.image-settings.$model") ?: config("contus.base.image.$model");

        if (!$config) {
            $config = config("settings.image-settings.default");
        }

        return ($config) ? ( object )$config : null;
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

    /**
     * This Method to find slug or id
     *
     * @return String
     */
    public function getKeySlugorId()
    {
        if (($this->request->header('x-request-type') == 'mobile') || (config()->get('auth.providers.users.table') !== 'customers')) {
            return 'id';
        } else {
            return 'slug';
        }
    }

    /**
     * Repository function to delete custom thumbnail of a video.
     *
     * @param integer $id
     * The id of the video.
     * @return boolean True if the thumbnail is deleted and false if not.
     */
    public function deleteCategoryImage($id)
    {
        /**
         * Check if category id exists.
         */
        if (!empty ($id)) {
            $category = $this->_category->findorfail($id);
            /**
             * Delete the image using the image path field from the database.
             */
            /**
             * Empty the image_url and image_path field in the database.
             */
            $category->image_url = '';
            $category->image_path = '';
            $category->save();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get headings for grid
     *
     * @vendor Contus
     *
     * @package Video
     * @return array
     */
    public function getGridHeadings()
    {
        return [StringLiterals::GRIDHEADING => [['name' => trans('video::categories.category_name'), StringLiterals::VALUE => StringLiterals::TITLE, 'sort' => true], ['name' => trans('video::categories.no_of_videos'), StringLiterals::VALUE => '', 'sort' => false], ['name' => trans('video::categories.parent_category'), StringLiterals::VALUE => '', 'sort' => false], ['name' => trans('video::categories.status'), StringLiterals::VALUE => StringLiterals::ISACTIVE, 'sort' => false], ['name' => trans('video::categories.added_on'), StringLiterals::VALUE => '', 'sort' => false], ['name' => trans('video::categories.action'), StringLiterals::VALUE => '', 'sort' => false]]];
    }
}