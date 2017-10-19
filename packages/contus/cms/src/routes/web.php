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


 Route::group ( [ 'prefix' => 'admin','namespace' => 'Contus\Cms\Http\Controllers\Admin' ], function () {
  
  Route::group ( [ 'middleware' => [ 'auth.admin','accesslevel'] ], function ()  {

  /** EmailContent Route **/
  Route::get('emails', 'EmailController@getIndex' );
  Route::get('emails/gridlist', 'EmailController@getGridlist');
  Route::get('emails/details-email-edit/{id}', 'EmailController@getDetailsEmailEdit');
  Route::get('emails', 'EmailController@getIndex' );
  Route::get('emails/gridlist', 'EmailController@getGridlist');

  /** StaticContent Route **/
  Route::get('staticContent', 'StaticContentController@getIndex' );
  Route::get('staticContent/gridlist', 'StaticContentController@getGridlist');
  Route::get('staticContent/edit-static-content/{id}', 'StaticContentController@getEditStaticContent');

  /** BannerContent Route **/
  Route::get('banner', 'BannerController@getIndex' );
  Route::get('banner/gridlist', 'BannerController@getGridlist');
  Route::post ( 'banner/banner-image', 'BannerController@postBannerImage' );
  Route::post ( 'banner/edit', 'BannerController@postEdit' );

  } );
 } );
   

Route::group(['middleware' => ['tokenauth', 'xcsrf']], function () {
    Route::group(['namespace' => 'Contus\Cms\Http\Controllers\Customer'], function () {
        Route::get('staticContentTemplate', 'StaticContentController@getStaticcontent');
    });
});
Route::group(['middleware' => ['tokenauth']], function () {
    Route::group(['namespace' => 'Contus\Cms\Http\Controllers\Customer'], function () {
        Route::get('static/{slug}', 'StaticContentController@getStaticFullContent');
    });
});
Route::group(['namespace' => 'Contus\Cms\Http\Controllers\Customer'], function () {
    Route::group(['middleware' => ['xcsrf']], function () {
        Route::get('blog', 'LatestNewsController@getBlog');
        Route::get('blogdetail', 'LatestNewsController@getBlogDetail');
    });
});

