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
Route::group ( [ 'prefix' => 'api/admin','namespace' => 'Contus\Video\Api\Controllers\Admin' ], function () {

    /*Live stream routes start*/
    Route::post ( 'createlivestream', 'LiveStreamController@createlivestream' );
    Route::post ( 'startlivestream', 'LiveStreamController@startLiveStream' );
    Route::post ( 'stoplivestream', 'LiveStreamController@stopLiveStream' );
    Route::post ( 'satuslivestream', 'LiveStreamController@statusLivestream' );
    /*Live stream routes end*/

    Route::group ( [ 'middleware' => 'api.admin' ], function () {
        Route::get('dashboard/info', 'DashboardController@getInfo');
        Route::post('image', 'VideoController@uploadImage');
        /* Videos Routes starts */
        Route::get('videos/info', 'VideoController@getInfo');
        Route::post('videos/records', 'VideoController@postRecords');
        Route::get('videos/video-to-edit/{id}', 'VideoController@getVideoToEdit');
        Route::get('videos/video-categories/{id}', 'VideoController@getEdit');
        Route::post('videos/update-status/{id}', 'VideoController@postUpdateStatus');
        Route::post('videos/edit/{id}', 'VideoController@postEdit');
        Route::post('videos/delete-action', 'VideoController@postDeleteAction');
        Route::post('videos/bulk-update-status', 'VideoController@postBulkUpdateStatus');
        Route::post('videos/thumbnail', 'VideoController@postThumbnail');
        Route::get('videos/complete-video-details/{id}', 'VideoController@getCompleteVideoDetails');
        Route::post('videos/handle-fine-uploader', 'VideoController@postHandleFineUploader');
        Route::post('videos/add', 'VideoController@postAdd');
        Route::post('videos/delete-thumbnail/{id}','VideoController@postDeleteThumbnail');
        Route::post('videos/subtitle', 'VideoController@postSubtitle');
        Route::post('videos/uplaod-banner-video','VideoController@postUplaodBannerVideo');
        /* Videos Routes ends */

        /*Live videos Routes start*/
        Route::get('livevideos/info', 'VideoController@getInfo');
        Route::post('livevideos/records', 'VideoController@postRecords');
        /*Live videos Routes end*/

        /* Category Routes starting*/
        Route::get('categories/info', 'CategoryController@getInfo');
        Route::post('categories/records', 'CategoryController@postRecords');
        Route::get('categories/videos/{id}', 'CategoryController@getVideoToEdit');
        Route::post('categories/parent-category/{id}', 'CategoryController@postParentCategory');
        Route::get('categories/video-categories/{id?}', 'CategoryController@getVideoCategories');
        Route::get('categories/updated-details', 'CategoryController@getUpdatedDetails');
        Route::post('categories/update-status/{id}', 'CategoryController@postUpdateStatus');
        Route::post('categories/edit/{id}', 'CategoryController@postEdit');
        Route::post('categories/action', 'CategoryController@postAction');
        Route::post('categories/bulk-update-status', 'CategoryController@postBulkUpdateStatus');
        Route::post('categories/add', 'CategoryController@postAdd');
        Route::post('categories/category-image', 'CategoryController@postCategoryImage');
        Route::post('categories/delete-category-image/{id}', 'CategoryController@postDeleteCategoryImage');
        /* Category Routes ending*/

        /*Playlists Routes Starting*/
        Route::post('playlists', 'PlaylistController@postAdd');
        Route::get('playlists/info', 'PlaylistController@getInfo');
        Route::post('playlists/records', 'PlaylistController@postRecords');
        Route::post('playlists/update-status/{id}', 'PlaylistController@postUpdateStatus');
        Route::post('playlists/edit/{id}', 'PlaylistController@postEdit');
        Route::post('playlists/action', 'PlaylistController@postDelete');
        Route::post('playlists/bulk-update-status', 'PlaylistController@postBulkUpdateStatus');
        Route::post('playlists/action', 'PlaylistController@postAction');
        Route::get('playlists/playlists-all', 'PlaylistController@getPlaylistList');
        Route::resource('playlists','PlaylistResourceController');
        Route::post('playlist/add', 'PlaylistController@postAdd');
        /*Playlists Routes End*/

        /*Genre Routes Starts*/
        Route::get('collections/info', 'CollectionController@getInfo');
        Route::post('collections/records', 'CollectionController@postRecords');
        Route::get('collections/video-to-edit/{id}', 'CollectionController@getVideoToEdit');
        Route::post('collections/update-status/{id}', 'CollectionController@postUpdateStatus');
        Route::post('collections/edit/{id}', 'CollectionController@postEdit');
        Route::post('collections/action', 'CollectionController@postAction');
        Route::post('collections/bulk-update-status', 'CollectionController@postBulkUpdateStatus');
        Route::post('collections/create-collection', 'CollectionController@postCreateCollection');
        /*Genre Routes End*/

        /*Sub genre Routes Start*/
        Route::get('examgroups/info', 'GroupController@getInfo');
        Route::post('examgroups/add', 'GroupController@postAdd');
        Route::post('examgroups/records', 'GroupController@postRecords');
        Route::get('examgroups/video-to-edit/{id}', 'GroupController@getVideoToEdit');
        Route::post('examgroups/update-status/{id}', 'GroupController@postUpdateStatus');
        Route::post('examgroups/edit/{id}', 'GroupController@postEdit');
        Route::post('examgroups/action', 'GroupController@postAction');
        Route::post('examgroups/bulk-update-status', 'GroupController@postBulkUpdateStatus');
        Route::get('examgroups/videos/{id}', 'GroupsController@getVideoCollections');
        /*Sub genre Routes End*/

        /*Presets Routes Start*/
        Route::get('presets/info', 'PresetController@getInfo');
        Route::post('presets/records', 'PresetController@postRecords');
        Route::get('presets/video-to-edit/{id}', 'PresetController@getVideoToEdit');
        Route::post('presets/update-status/{id}', 'PresetController@postUpdateStatus');
        Route::post('presets/edit/{id}', 'PresetController@postEdit');
        Route::post('presets/delete-action', 'PresetController@postDeleteAction');
        Route::post('presets/bulk-update-status', 'PresetController@postBulkUpdateStatus');
        /*Presets Routes End*/

        /*Comments Routes Start*/
        Route::get('comments/info', 'CommentsController@getInfo');
        Route::post('comments/records', 'CommentsController@postRecords');
        Route::get('comments/video-to-edit/{id}', 'CommentsController@getVideoToEdit');
        Route::post('comments/updatestatus/{id}', 'CommentsController@postUpdateStatus');
        Route::post('comments/update-status/{id}', 'CommentsController@postUpdateStatus');
        Route::post('comments/edit/{id}', 'CommentsController@postEdit');
        Route::post('comments/delete-action', 'CommentsController@postDeleteAction');
        Route::post('comments/bulk-update-status', 'CommentsController@postBulkUpdateStatus');
        /*Comments Routes End*/

        /*Queries Routes Start*/
        Route::get('qa/info', 'QaController@getInfo');
        Route::post('qa/records', 'QaController@postRecords');
        Route::get('qa/video-to-edit/{id}', 'QaController@getVideoToEdit');
        Route::post('qa/updatestatus/{id}', 'QaController@postUpdateStatus');
        Route::post('qa/update-status/{id}', 'QaController@postUpdateStatus');
        Route::post('qa/edit/{id}', 'QaController@postEdit');
        Route::post('qa/delete-action', 'QaController@postDeleteAction');
        Route::post('qa/bulk-update-status', 'QaController@postBulkUpdateStatus');
        /*Queries Routes End*/

        /*Reports Routes Start*/
        Route::get('/reports', 'ReportsController@getIndex');
        Route::prefix ('reports/info')->group(function () {
            Route::get('/{time1?}/{time2?}/{time3?}', 'ReportsController@getInfo');
        });
        /*Reports Routes End*/
    } );
} );


Route::group ( [ 'prefix' => 'api/admin','namespace' => 'Contus\Video\Api\Controllers\Frontend' ], function () {
    Route::post ( 'comments/{slug}', 'VideoController@browseVideoComments' );
    Route::post ( 'qa/{slug}', 'VideoController@browseVideoQA' );
} );

Route::group ( [ 'prefix' => 'api/v1','namespace' => 'Contus\Video\Api\Controllers\Frontend' ], function (){
    Route::post ( 'subscriptions', 'VideoListingController@getAllSubscriptions' );
    Route::post ( 'single/{slug}', 'VideoListingController@getOneSubscriptions' );
    Route::post ( 'search', 'VideoListingController@search' );
    Route::get ( 'category', 'VideoListingController@getAllCategory' );
    Route::post ( 'video-details/{slug}', 'VideoListingController@getVideos' );
    Route::get ( 'video-details/{slug}', 'VideoListingController@getVideos' );
    
    
    Route::post ( 'videos/{slug}', 'VideoController@browseVideo' );
    Route::get ( 'videos/{slug}', 'VideoController@browseVideo' );
    Route::post ( 'videos/related/{slug}', 'VideoController@browseVideoRelated' );
    Route::post ( 'videos/videolike/{slug}', 'VideoController@browseVideoLikes' );
    Route::post ( 'videos/watchlater/{slug}', 'VideoController@watchlater' );
    Route::post ( 'videos/comments/{slug}', 'VideoController@browseVideoComments' );
    Route::post ( 'videoComments', 'VideoController@getandpostVideocomments' );
    Route::post ( 'videoQA', 'VideoController@postQuestionsAndAnswers' );
    Route::post ( 'videos/playlist/{slug}', 'PlaylistController@browsePlaylistList' );
    Route::get ( 'myPreferenceList', 'PlaylistController@preferenceListPlaylist');
    Route::put ( 'savemyPreferenceList', 'PlaylistController@savepreferenceListPlaylist');
    Route::post ( 'savemyPreferenceList', 'PlaylistController@savepreferenceListPlaylist');
    Route::get ( 'myPreferenceCategoryList', 'PlaylistController@mypreferenceCategoryList');
    
} );
Route::group ( [ 'prefix' => 'api/v1','namespace' => 'Contus\Video\Api\Controllers\Frontend' ], function (){
    Route::post ( 'videos', 'VideoController@browseVideos' );
    Route::get ( 'videos', 'VideoController@browseVideos' );
    Route::post ( 'playlist', 'PlaylistController@browseCategoryPlaylist' );
    Route::post ( 'exams', 'CollectionController@browseCategoryExams' );
    Route::post ( 'forgotpassword', 'PlaylistController@forgotpassword' );
    Route::get ( 'getCategoryForNav', 'CategoryController@getCategoriesNav' );
    Route::get ( 'categoryVideos', 'VideoController@browseVideos' );
    Route::post ( 'searchRelatedVideos', 'VideoController@searchRelatedVideos' );
    Route::get ( 'videos/section/{slug}', 'VideoController@getCategorySection' );
  
    Route::post ( 'videosRelatedTrending', 'VideoController@browseRelatedTrendingVideos' );
} );
    Route::group ( [ 'prefix' => 'api/v1','namespace' => 'Contus\Video\Api\Controllers\Frontend' ], function (){
        Route::group ( [ 'middleware' => [ 'api' ] ], function (){
           Route::get ( 'home', 'VideoController@postDashboard' );
        });
    } );