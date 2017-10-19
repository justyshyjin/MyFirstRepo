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

Route::group ( [ 'prefix' => 'api/admin','namespace' => 'Contus\Notification\Api\Controllers\Notification' ], function (){
    Route::group ( [ 'middleware' => [ 'api.admin','accesslevel' ] ], function (){
        //Route::controller ( 'notifications', 'NotificationController' );
    } );
    Route::post ( 'notify', 'NotificationController@setNotification' );
} );

Route::group ( [ 'prefix' => 'api/v1','namespace' => 'Contus\Notification\Api\Controllers\Notification' ], function (){
    Route::group ( [ 'middleware' => [ 'api.auth'] ], function (){
        Route::post ( 'notification/updatesettings', 'NotificationController@postNotificationSettings' );
        Route::post ('notification/isRead','NotificationController@isReadNotifications' );
    } );
} );
Route::group ( [ 'prefix' => 'api/v1','namespace' => 'Contus\Notification\Api\Controllers\Notification' ], function (){
    Route::group ( [ 'middleware' => [ 'api.auth'] ], function (){
        Route::get ( 'notifications', 'NotificationController@getNotifications' );
        Route::post ( 'notifications', 'NotificationController@getNotifications' );
        Route::post ( 'notify', 'NotificationController@setNotification' );
    } );
} );
/**
 *  Api route url version 2 created for mobile update
 *
 */
Route::group ( [ 'prefix' => 'api/v2','namespace' => 'Contus\Notification\Api\Controllers\Notification' ], function (){
    Route::group ( [ 'middleware' => [ 'updatedversion','api.auth'] ], function (){
        Route::post ( 'notification/updatesettings', 'NotificationController@postNotificationSettings' );
        Route::post ('notification/isRead','NotificationController@isReadNotifications' );
    } );
} );
Route::group ( [ 'prefix' => 'api/v2','namespace' => 'Contus\Notification\Api\Controllers\Notification' ], function (){
    Route::group ( [ 'middleware' => [ 'updatedversion','api.auth'] ], function (){
        Route::get ( 'notifications', 'NotificationController@getNotifications' );
        Route::post ( 'notify', 'NotificationController@setNotification' );
    } );
} );