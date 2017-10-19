<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::namespace('Contus\User\Http\Controllers\Admin')->group( function() {
 
 Route::prefix('admin')->group(function () {
  
  Route::get('/', ['as' => 'admin', 'uses' => 'AuthController@getLogin']);
  Route::post('auth/login', ['as' => 'admin/auth/login.post', 'uses' => 'AuthController@postLogin']);
  Route::get('auth/login', ['as' => 'admin/auth/login.get', 'uses' => 'AuthController@getLogin']);
  Route::post('auth/logout', ['as' => 'admin/auth/logout.post', 'uses' => 'AuthController@logout']);
  Route::get( 'auth/forgot-password', 'AuthController@getForgotPassword' );
  Route::post( 'auth/forgot-password', 'AuthController@postForgotPassword' );
  Route::get('users/profile', 'AdminUserController@getProfile' );
  
  Route::middleware(['auth.admin','accesslevel'])->group(function () {
    // Login Routes...
    Route::get('settings', 'SettingsController@getIndex' );
    Route::post('settings/update', 'SettingsController@postUpdate' );
    Route::get('users/changepassword','AdminUserController@getChangepassword');
   
    /* User package Route and Admin Group Route*/   
        Route::get('users', 'AdminUserController@getIndex');
        Route::get('users/gridlist', 'AdminUserController@getGridlist');
        Route::get('groups', 'AdminUserGroupController@getIndex');
        Route::get('groups/gridlist', 'AdminUserGroupController@getGridlist');
        Route::get('groups/add', 'AdminUserGroupController@getAdd');
        Route::post('groups/add', 'AdminUserGroupController@postAdd');
        Route::get('groups/edit/{id}', 'AdminUserGroupController@getEdit');
        Route::post('groups/update/{id}', 'AdminUserGroupController@postUpdate');
        
  });
        
    });


// Registration Routes...
  Route::get('register', ['as' => 'register', 'uses' => 'Auth\RegisterController@showRegistrationForm']);
    Route::post('register', ['as' => 'register.post', 'uses' => 'Auth\RegisterController@register']);

    Route::get('password/reset', ['as' => 'password.reset', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm']);
    Route::post('password/email', ['as' => 'password.email', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail']);
    Route::get('password/reset/{token}', ['as' => 'password.reset.token', 'uses' => 'Auth\ResetPasswordController@showResetForm']);

    Route::post('password/reset', ['as' => 'password.reset.post', 'uses' => 'Auth\ResetPasswordController@reset']); 
});


