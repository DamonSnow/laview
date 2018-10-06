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
app('api.exception')->register(function (Exception $exception) {
    $request = Illuminate\Http\Request::capture();
    return app('App\Exceptions\Handler')->render($request, $exception);
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function($api) {
    $api->get('version', function() {

        return response('this is version v1');
    });
    $api->group(['middleware'=>['auth:api']],function($api){
        $api->post('logout','App\Http\Controllers\Api\AuthenticateController@logout');
        $api->get('get_info','App\Http\Controllers\Api\UserController@getInfo')->name('get_info');

    });

    $api->get('users','App\Http\Controllers\Api\UserController@users');
    $api->get('users/{id}','App\Http\Controllers\Api\UserController@getUser');

    $api->post('login','App\Http\Controllers\Api\AuthenticateController@login');

    $api->post('refresh','App\Http\Controllers\Api\AuthenticateController@refresh');
});

$api->version('v2', function($api) {
    $api->get('version', function() {
        return response('this is version v2');
    });
});
