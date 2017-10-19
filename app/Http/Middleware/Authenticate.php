<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Config;
use Contus\Customer\Models\Customer;

class Authenticate {
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param Guard $auth
     * @return void
     */
    public function __construct(Guard $auth) {
        Config::set ( 'auth.providers.users.model', Customer::class );
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if ($this->auth->guest ()) {
            if ($request->ajax ()) {
                return response ( 'Unauthorized.', 401 );
            } else {
                return redirect ()->guest ( url().'#/login' );
            }
        }

        return $next ( $request );
    }
}
