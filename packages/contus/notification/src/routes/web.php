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

Route::group ( [ 'middleware' => [ 'tokenauth' ] ], function (){
    Route::group ( [ 'namespace' => 'Contus\Notification\Http\Controllers\Notification' ], function (){
        Route::group ( [ 'middleware' => [ 'auth','xcsrf' ] ], function (){
            Route::get ( 'notifications', 'NotificationController@index' );
        } );
    } );
} );