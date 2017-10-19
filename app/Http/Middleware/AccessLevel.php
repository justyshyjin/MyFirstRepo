<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Auth\Guard;

class AccessLevel
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;
    
    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
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
        $response = $next($request);
        
        if (Gate::denies('access', $this->auth)) { 
            if(app('request')->header ( 'X-WEB-SERVICE' ) == 'true'){
                $request->session()->flash(
                    "error","You are not authorized to perform this action. Please contact your admin."
                );

                $response = response()->json([
                    "message" => "You are not authorized to perform this action. Please contact your admin.",
                    "accessFailure" => 1,
                    "redirectTo" => url('admin/dashboard/index?permission=1')
                ],403);
            } else {
                $response = redirect('admin/dashboard')
                            ->withErrors ( "You are not authorized to perform this action. Please contact your admin." );   
            }
        }
        return $response;
    }
}