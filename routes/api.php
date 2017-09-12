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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

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
        $api->post('create', ['as' => 'api.activity.create', 'uses' => 'ActivityController@create']);
        $api->get('all', ['as' => 'api.activity.all', 'uses' => 'ActivityController@all']);

        $api->group(['prefix' => 'label'], function($api) {
            $api->post('create', ['as' => 'api.activity.label.create', 'uses' => 'ActivityLabelController@create']);
            $api->get('all', ['as' => 'api.activity.label.all', 'uses' => 'ActivityLabelController@all']);
            $api->post('delete', ['as' => 'api.activity.label.delete', 'uses' => 'ActivityLabelController@destory']);
        });
    });
});
