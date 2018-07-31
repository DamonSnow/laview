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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function($api) {
    $api->get('version', function() {

        return response('this is version v1');
    });


    $api->get('get_info','App\Http\Controllers\Api\UserController@getInfo');
    $api->get('users/{id}','App\Http\Controllers\Api\UserController@getUser');
    $api->get('users','App\Http\Controllers\Api\UserController@users');
    $api->post('login','App\Http\Controllers\Api\AuthenticateController@login');
});

$api->version('v2', function($api) {
    $api->get('version', function() {
        return response('this is version v2');
    });
});
