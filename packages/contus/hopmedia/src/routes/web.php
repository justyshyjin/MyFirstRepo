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

Route::prefix('hopmedia')->namespace('Contus\Hopmedia\Http\Controllers\Hopmedia')->group(function () {
	Route::get('/', 'DashboardController@Index');
	Route::get('dashboard', 'DashboardController@dashboard');

	Route::get('features', 'DashboardController@Features');
	Route::get('pricing', 'DashboardController@Pricing');
	Route::get('aboutUs', 'DashboardController@AboutUs');
	Route::get('contact', 'DashboardController@ContactUs');
	Route::get('privacy-policy', 'DashboardController@PrivacyPolicy');
	Route::get('terms-condition', 'DashboardController@TermsCondition');

});

Route::group ( [ 'prefix' => 'hopmedia/auth','namespace' => 'Contus\Hopmedia\Http\Controllers\User' ], function() {
	Route::post ( 'login', 'UserAuthController@postLogin' );
		
	Route::group(['middleware' => ['tokenauth']], function () { 
		Route::get ( 'logout', 'UserAuthController@getLogout' );
	});
});