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

Route::group ( [ 'prefix' => 'auth','namespace' => 'Contus\Customer\Http\Controllers\Customer' ], function () {

    Route::get ( 'logout', 'CustomerAuthController@getLogout' );  
    Route::group(['middleware' => ['tokenauth']], function () { 
        Route::post ( 'login', 'CustomerAuthController@postLogin' );
        Route::get ( 'google', 'CustomerAuthController@getGoogle' );
        Route::get ( 'google-callback', 'CustomerAuthController@getGoogleCallback' );
        Route::get ( 'facebook', 'CustomerAuthController@getFacebook' );
        Route::get ( 'facebook-callback', 'CustomerAuthController@getFacebookCallback' );
        Route::get ( 'change_password/{random}', 'CustomerAuthController@getChangePassword' );
        Route::post ( 'change_password/{random}', 'CustomerAuthController@postsaveNewpassword' );    
    } );
} );
Route::group ( [ 'namespace' => 'Contus\Customer\Http\Controllers\Customer' ], function () {

    Route::get ( 'forgotPassword/{random}', 'CustomerAuthController@getChangePassword' );
    Route::post ( 'forgotPassword/{random}', 'CustomerAuthController@postsaveNewpassword' );
    Route::get ( 'customer/changepassword', 'CustomerAuthController@postNewpassword' );
    Route::post ( 'savenewpassword', 'CustomerAuthController@postsaveNewpassword' );
} );

    Route::group ( [ 'namespace' => 'Contus\Customer\Http\Controllers\Dashboard' ], function ()  {
        Route::get ( '/', 'DashboardController@index' );
        Route::get ( 'dashboard', 'DashboardController@dashboard' );
        Route::get ( 'loginModel', 'DashboardController@loginModel' );
        Route::get ( 'signUpModel', 'DashboardController@signUpModel' );
        Route::get ( 'forgotModel', 'DashboardController@forgotModel' );
        Route::get ( 'newpasswordModel', 'DashboardController@newpasswordModel' );
    } );

    Route::group ( [ 'namespace' => 'Contus\Customer\Http\Controllers\Account' ], function () {

        Route::group ( [ 'middleware' => [ 'auth' ] ], function () {
            Route::get ( 'subscriptions', 'SubscriptionApiController@getGridlist' );
            Route::get ( 'profile', 'MyAccountController@index' );
            Route::get ( 'myprofile', 'MyAccountController@myprofile' );
            Route::get ( 'password', 'MyAccountController@changePassword' );
            Route::get ( 'editProfile', 'MyAccountController@editProfile' );
            Route::get ( 'subscribeinfo', 'MyAccountController@subscribeDetails' );
            Route::get ( 'favourites', 'MyAccountController@favourites' );
            Route::get ( 'following', 'MyAccountController@following' );
        } );
    } );


 Route::prefix('admin')->namespace('Contus\Customer\Http\Controllers\Customer')->group( function() {
  Route::group ( [ 'middleware' => [ 'auth.admin','accesslevel' ] ], function (){
   
 Route::get('customer', 'CustomerUserController@getIndex');
 Route::get('customer/gridlist', 'CustomerUserController@getGridlist');
 });
 });
  /**Subscription Route */
  Route::prefix('admin')->namespace('Contus\Customer\Http\Controllers\Admin')->group( function() {
   Route::group ( [ 'middleware' => [ 'auth.admin','accesslevel' ] ], function () {
    
    Route::get('subscriptions-plans','SubscriptionPlanController@getIndex');
    Route::get('subscriptions-plans/gridlist','SubscriptionPlanController@getGridlist');
    
   });
  });
   

