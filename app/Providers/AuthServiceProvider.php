<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Contus\User\Models\User;
use Contus\Customer\Models\Customer;

class AuthServiceProvider extends ServiceProvider {
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [ 'App\Model' => 'App\Policies\ModelPolicy' ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param \Illuminate\Contracts\Auth\Access\Gate $gate
     * @return void
     */
    public function boot(GateContract $gate) {
        if (env ( "LS_HOST" ) == 1) {
            if (($this->app ['request']->header ( 'host' ) === env ( "LS_TYPE_ADMIN" )) === false) {
                config ()->set ( 'auth.model', Customer::class );
                config ()->set ( 'auth.providers.users.table', 'customers' );
                config ()->set ( 'session.cookie', '_ls_s' );
            }
        }else{ 
            if (strpos ( $this->app ['request']->path (), 'admin' ) === false && strpos ( $this->app ['request']->path (), 'hopmedia' )=== false) { 
                config ()->set ( 'auth.providers.users.model', Customer::class );
                config ()->set ( 'auth.providers.users.table', 'customers' );
                config ()->set ( 'session.cookie', '_ls_s' );
            } else {
                config ()->set ( 'auth.providers.users.model', User::class );
                config ()->set ( 'auth.providers.users.table', 'users' );
                config ()->set ( 'session.cookie', '_ls_ss' );
            }
        }
        $this->registerPolicies ( $gate );
        $gate->define ( 'access', function ($user) {
         if ($user->hasAccess (  Route::currentRouteAction ())) {
                return true;
            }
            return false;
        } );
    }
}
