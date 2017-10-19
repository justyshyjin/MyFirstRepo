<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Contus\User\Models\User;
class ApiAdminAuthenticate
{
    /**
     * Class property to hold the request header
     *
     * @var obj
     */
    protected $header = NULL;
    /**
     * Class property the access token error
     *
     * @var int
     */
    protected $access_token_error = 0;
    /**
     * Class property the access token
     *
     * @var string
     */
    protected $access_token = NULL;
    /**
     * Class property the public access token
     *
     * @var string
     */

    protected $user_id = NULL;
    /**
     * Create a new filter instance.
     */
    protected $request_type = NULL;
    /**
     * It is used to differenciate the request type.
     */
    public function __construct() {
        $this->header =Request::header();

    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
    /**
     * Split header values based on the type
     *
     * @param $type
     *
     * @return bool | string
     */
    public function splitHeaderTokens($type) {
        if(isset($this->header[$type][0]) && !empty($this->header[$type][0])) {
            return $this->header[$type][0];
        }
        return false;
    }
}