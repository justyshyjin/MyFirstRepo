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
Route::prefix('api/admin')->namespace('Contus\Payment\Api\Controllers\Payment')->group(function () {

    /** Transaction Route */
    Route::get('transactions/info', 'TransactionController@getInfo');
    Route::post('transactions/records', 'TransactionController@postRecords');
    Route::get('transactions/complete-transaction-details/{id}', 'TransactionController@getCompleteTransactionDetails');

    /**Payment Route */
    Route::get('payments/info', 'PaymentController@getInfo');
    Route::post('payments/records', 'PaymentController@postRecords');
    Route::post('payments/edit/{id}', 'PaymentController@postEdit');
    Route::post('payments/update-status/{id}', 'PaymentController@postUpdateStatus');
    Route::post('payments/update-mode/{id}', 'PaymentController@postUpdateMode');

});

Route::group(['prefix' => 'api/v1', 'namespace' => 'Contus\Payment\Api\Controllers\Payment'], function () {
    Route::group(['middleware' => ['api.auth']], function () {
        Route::get('transactions/info', 'TransactionController@getInfo');
        Route::post('transactions/records', 'TransactionController@postRecords');
        Route::resource('myplan', 'TransactionResourceController');
    });
    Route::post('getrsaResponse', 'PaymentController@getRsaresponse');
});
/**
 *  Api route url version 2 created for mobile update
 *
 */
Route::group(['prefix' => 'api/v2', 'namespace' => 'Contus\Payment\Api\Controllers\Payment'], function () {
    Route::group(['middleware' => ['updatedversion', 'api.auth']], function () {
        Route::resource('myplan', 'TransactionResourceController');
    });
    Route::post('getrsaResponse', 'PaymentController@getRsaresponse');
});