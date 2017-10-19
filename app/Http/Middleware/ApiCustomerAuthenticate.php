<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Contus\Customer\Models\Customer;

class ApiCustomerAuthenticate {
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
        $this->header = Request::header ();
    }
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $this->request_type = $this->splitHeaderTokens ( 'x-request-type' );
        if (($this->user_id = $this->splitHeaderTokens ( 'x-user-id' ))) {
            if (($this->access_token = $this->splitHeaderTokens ( 'x-access-token' ))) {
                $user = Customer::where ( 'access_token', $this->access_token )->where ( 'id', $this->user_id )->where ( 'is_active', 1 )->first ();
                if (empty ( $user ) && count ( $user ) == 0) {
                    return Response::json ( array ('error' => true,'responseCode' => 403,'status' => 'error','message' => 'You have already logged in  with other device.  Please login again to continue.' ), 403 );
                }
                if (! is_null ( $this->request_type ) && $this->request_type == 'mobile') {
                    auth ()->loginUsingId ( $user->id );
                } else {
                    if (auth ()->user ()) {
                        if ((auth()->user()->is_active === 0) || auth ()->user ()->access_token !== request ()->session ()->get ( 'access_token' )) {
                            request ()->session ()->flash ( 'multiple_login' ,"You have already logged in  with other device.  Please login again to continue.");
                            auth ()->logout ();
                            return Response::json ( array ('error' => true,'responseCode' => 403,'status' => 'error','message' => 'You have already logged in  with other device.  Please login again to continue.' ), 403 );
                        }
                    }
                }
                $request ['user_id'] = $this->user_id;
            } else {
                return Response::json ( array ('error' => true,'responseCode' => 403,'status' => 'error','message' => 'Please login or signup to continue.' ), 403 );
            }
        } else {
            return Response::json ( array ('error' => true,'responseCode' => 401,'status' => 'error','message' => 'Please login or signup to continue.' ), 401 );
        }

        return $next ( $request );
    }
    /**
     * Split header values based on the type
     *
     * @param
     * $type
     *
     * @return bool | string
     */
    public function splitHeaderTokens($type) {
        if (isset ( $this->header [$type] [0] ) && ! empty ( $this->header [$type] [0] )) {
            return $this->header [$type] [0];
        }
        return false;
    }
}