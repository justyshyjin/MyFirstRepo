<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'api/admin/cms', 'namespace' => 'Contus\Cms\Api\Controllers\Cms'], function () {

    Route::group(['middleware' => ['api.admin', 'accesslevel']], function () {
        Route::resource('email', 'EmailResourceController');
        Route::resource('sms', 'SmsResourceController');
        Route::resource('static', 'StaticResourceController');
        Route::resource('latestnews', 'LatestNewsResourceController');
        Route::resource('contactus', 'ContactusController');
    });
});

Route::group(['prefix' => 'api/admin', 'namespace' => 'Contus\Cms\Api\Controllers\Cms'], function () {
    Route::group(['middleware' => ['api.admin']], function () {

        /** EmailContent Route */
        Route::get('emails/info', 'EmailController@getInfo');
        Route::post('emails/records', 'EmailController@postRecords');
        Route::get('emails/email-data/{id}', 'EmailController@getEmailData');
        Route::post('emails/edit/{id}', 'EmailController@postEdit');

        /** StaticContent Route */
        Route::get('staticContent/info', 'StaticContentController@getInfo');
        Route::post('staticContent/records', 'StaticContentController@postRecords');
        Route::get('staticContent/static-data/{id}', 'StaticContentController@getStaticData');
        Route::post('staticContent/edit/{id}', 'StaticContentController@postEdit');
        Route::post('staticContent/banner-image/', 'StaticContentController@postStaticBannerImage');
        Route::post('staticContent/delete-banner-image/{id}', 'StaticContentController@postDeleteStaticBannerImage');
        
        /** BannerContent Route */
        Route::get('banner/info', 'BannerController@getInfo');
        Route::post('banner/records', 'BannerController@postRecords');
        Route::post('banner/banner-image', 'BannerController@postBannerImage');
        Route::post('banner/edit/{id}', 'BannerController@postEdit');

    });
});

Route::group(['prefix' => 'api/v1', 'namespace' => 'Contus\Cms\Api\Controllers\Staticcontent'], function () {
    Route::get('staticcontent/{slug}', 'StaticContentController@getStaticContent');
    Route::get('getstaticcontentrules', 'StaticContentController@getStaticContentRules');
    Route::get('getsiteaddress', 'StaticContentController@getSiteAddress');
});

Route::group(['prefix' => 'api/v1', 'namespace' => 'Contus\Cms\Api\Controllers\Cms'], function () {
    Route::get('testimonial', 'TestimonialController@getTestimonialList');
    Route::post('staticContent/contactus', 'ContactusController@postContact');
    Route::post('staticContent/feedback', 'ContactusController@postFeedback');
    Route::get('blog', 'LatestNewsController@getData');
    Route::get('blogdetail/{slug}', 'LatestNewsController@getBlogDetail');
    Route::get('aboutUs', 'LatestNewsController@getAboutUs');
});

/**
 *  Api route url version 2 created for mobile update
 *
 */
Route::group(['prefix' => 'api/v2', 'namespace' => 'Contus\Cms\Api\Controllers\Staticcontent'], function () {
    Route::group(['middleware' => ['updatedversion']], function () {
        Route::get('staticcontent/{slug}', 'StaticContentController@getStaticContent');
        Route::get('getstaticcontentrules', 'StaticContentController@getStaticContentRules');
        Route::get('getsiteaddress', 'StaticContentController@getSiteAddress');
    });
});
Route::group(['prefix' => 'api/v2', 'namespace' => 'Contus\Cms\Api\Controllers\Cms'], function () {
    Route::group(['middleware' => ['api.admin', 'accesslevel']], function () {
        Route::get('testimonial', 'TestimonialController@getTestimonialList');
        Route::post('staticContent/contactus', 'ContactusController@postContact');
        Route::get('blog', 'LatestNewsController@getData');
        Route::get('blogdetail/{slug}', 'LatestNewsController@getBlogDetail');
        Route::get('aboutUs', 'LatestNewsController@getAboutUs');
    });
});