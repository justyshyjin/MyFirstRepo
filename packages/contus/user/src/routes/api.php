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

Route::prefix('api/admin')->namespace('Contus\User\Api\Controllers\Admin')->group(function () {
    Route::group(['middleware' => ['auth.admin']], function () {

        /** UserModule Route **/
        Route::get('users/info', 'AdminUserController@getInfo');
        Route::post('users/records', 'AdminUserController@postRecords');
        Route::post('users/add', 'AdminUserController@postAdd');
        Route::post('users/edit/{id}', 'AdminUserController@postEdit');
        Route::post('users/action', 'AdminUserController@postAction');
        Route::post('users/changepassword', 'AdminUserController@postChangepassword');
        Route::get('users/change-password-info', 'AdminUserController@getChangePasswordInfo');
        Route::post('users/update-status/{id}', 'AdminUserController@postUpdateStatus');

        /** User Profile Route **/
        Route::post('users/delete-profile-image/{id}', 'AdminUserController@postDeleteProfileImage');
        Route::post('users/profile-image', 'AdminUserController@postProfileImage');
        Route::get('users/edit', 'AdminUserController@getEdit');

        /** AdminGroups Route **/
        Route::post('groups/records', 'AdminUserGroupController@postRecords');
        Route::post('groups/action', 'AdminUserGroupController@postAction');
        Route::post('groups/edit/{id}', 'AdminUserGroupController@postAction');
    });
});
