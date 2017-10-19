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

Route::prefix('admin')->namespace('Contus\Video\Http\Controllers\Admin')->group(function () {
 Route::get('dashboard', 'DashboardController@getIndex');
 
    Route::group(['middleware' => ['auth.admin','accesslevel']], function () {

        Route::get('videos', 'VideoController@getIndex');
        Route::get('videos/add', 'VideoController@getAdd');
        Route::get('videos/gridlist', 'VideoController@getGridlist');
        Route::get('videos/details-video-edit/{id}', 'VideoController@getDetailsVideoEdit');
        Route::get('videos/view-details-video/{id}', 'VideoController@getViewDetailsVideo');

        Route::get('livevideos', 'VideoController@getIndex');
        Route::get('livevideos/gridlist', 'VideoController@getGridlist');
        Route::get('livevideos/details-video-edit/{id}', 'VideoController@getDetailsVideoEdit');
        Route::get('livevideos/view-details-video/{id}', 'VideoController@getViewDetailsVideo');

        Route::get('categories', 'CategoryController@getIndex');
        Route::get('categories/gridlist', 'CategoryController@getGridlist');
        Route::get('categories/videos/{id}', 'CategoryController@getVideos');

        Route::get('playlists', 'PlaylistsController@getIndex');
        Route::get('playlists/gridlist', 'PlaylistsController@getGridlist');
        Route::get('playlists/videos/{id}', 'PlaylistsController@getVideos');

        Route::get('collections', 'CollectionController@getIndex');
        Route::get('collections/gridlist', 'CollectionController@getGridlist');
        Route::get('collections/details-video-edit/{id}', 'CollectionController@getDetailsVideoEdit');

        Route::get('examgroups', 'GroupsController@getIndex');
        Route::get('examgroups/gridlist', 'GroupsController@getGridlist');
        Route::get('examgroups/videos/{id}', 'GroupsController@getVideos');
        Route::get('examgroups/details-video-edit/{id}', 'GroupsController@getDetailsVideoEdit');

        Route::get('presets', 'PresetController@getIndex');
        Route::get('presets/gridlist', 'PresetController@getGridlist');
        Route::get('presets/details-video-edit/{id}', 'PresetController@getDetailsVideoEdit');

        Route::get('comments', 'CommentsController@getIndex');
        Route::get('comments/gridlist', 'CommentsController@getGridlist');
        Route::get('comments/details-video-edit/{id}', 'CommentsController@getDetailsVideoEdit');

        Route::get('qa', 'QaController@getIndex');
        Route::get('qa/gridlist', 'QaController@getGridlist');
        Route::get('qa/details-video-edit/{id}', 'QaController@getDetailsVideoEdit');

        Route::get('youtube-live', 'YoutubeImportController@getLive');
        Route::get( 'youtube-import', 'YoutubeImportController@getIndex' );
        Route::get( 'youtube-import/download', 'YoutubeImportController@getDownload' );

        Route::get('/reports', 'ReportsController@getIndex');
    });
});
Route::group ( [ 'namespace' => 'Contus\Video\Http\Controllers\Customer' ], function () {
    Route::group ( [ 'middleware' => [ 'xcsrf' ] ], function (){
        Route::get ( 'playlist', 'VideoController@playlistIndex' );
        Route::get ( 'allPlaylists', 'VideoController@allPlaylists' );
        Route::get ( 'livevideos', 'VideoController@livevideos' );
        Route::get ( 'videos', 'VideoController@index' );
        Route::get ( 'video', 'VideoController@video' );
        Route::get ( 'videodetail', 'VideoController@videodetail' );
        Route::get ( 'allvideos', 'VideoController@allvideos' );
        Route::get ( 'playlistdetail', 'VideoController@playlistVideos' );
        Route::get ( 'listCategories', 'VideoController@listCategories' );
        Route::get ( 'grouplistdetail', 'VideoController@groupList' );
        Route::get ( 'grouplistdetail', 'VideoController@groupList' );
        Route::get ( 'groupvideodetail', 'VideoController@groupvideodetail' );
        Route::get ( 'playlistlistdetail', 'VideoController@playlistvideodetail' );
        Route::get ( 'videodetailsidemenu', 'VideoController@videodetailsidemenu' );
    } );
} );

Route::group ( [ 'namespace' => 'Contus\Video\Http\Controllers\Customer','middleware' => [ 'auth','xcsrf' ] ], function (){
    Route::get ( 'video', 'VideoController@video' );
    Route::get ( 'videodetail', 'VideoController@videodetail' );
} );