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
Route::group(['middleware' => ['tokenauth']], function () {
    Route::group(['namespace' => 'Contus\Payment\Http\Controllers\Admin'], function () {
        Route::group(['middleware' => ['auth', 'xcsrf']], function () {
            Route::get('transactions', 'CustomerTransactionController@getIndex');
            Route::get('transactions/gridlist', 'CustomerTransactionController@getGridlist');
            Route::get('transactions/transaction-details/{id}', 'CustomerTransactionController@getTransactionDetails');
        });
    });
});

Route::prefix('admin')->namespace('Contus\Payment\Http\Controllers\Admin')->group(function () {
    Route::group(['middleware' => ['auth.admin', 'accesslevel']], function () {

        Route::get('transactions', 'TransactionController@getIndex');
        Route::get('transactions/gridlist', 'TransactionController@getGridlist');
        Route::get('transactions/transaction-details/{id}', 'TransactionController@getTransactionDetails');

        Route::get('payments', 'PaymentController@getIndex');
        Route::get('payments/gridlist', 'PaymentController@getGridlist');

    });
});
 