<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
});

//
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function($api) {
    $api->group(['namespace' => 'App\Api\Version1\Controllers', 'prefix' => 'auth'], function($api) {
        $api->post('login', ['as' => 'api.auth.login', 'uses' => 'AuthController@login']);
    });

    $api->group(['namespace' => 'App\Api\Version1\Controllers', 'prefix' => 'user', 'middleware' => 'api.auth'], function($api) {
        $api->post('me', ['as' => 'api.user.me', 'uses' => 'UserController@me']);
    });

    $api->group(['namespace' => 'App\Api\Version1\Controllers', 'prefix' => 'dashboard', 'middleware' => 'api.auth'], function($api) {
        $api->post('create', ['as' => 'api.dashboard.create', 'uses' => 'DashboardController@create']);
        $api->get('all', ['as' => 'api.dashboard.all', 'uses' => 'DashboardController@all']);
    });

    $api->group(['namespace' => 'App\Api\Version1\Controllers', 'prefix' => 'bookmark', 'middleware' => 'api.auth'], function($api) {
        $api->post('create', ['as' => 'api.bookmark.create', 'uses' => 'BookmarkController@create']);
        $api->get('all', ['as' => 'api.bookmark.all', 'uses' => 'BookmarkController@all']);
    });

    $api->group(['namespace' => 'App\Api\Version1\Controllers', 'prefix' => 'activity', 'middleware' => 'api.auth'], function($api) {
        $api->group(['prefix' => 'label'], function($api) {
            $api->post('create', ['as' => 'api.activity.label.create', 'uses' => 'ActivityLabelController@create']);
            $api->get('all', ['as' => 'api.activity.label.all', 'uses' => 'ActivityLabelController@all']);
        });
    });
});
