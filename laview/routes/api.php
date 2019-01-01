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

$api->version('v1', function ($api) {
    $api->get('version', function () {

        return response('this is version v1');
    });
    $api->group(['middleware' => ['auth:api', 'cors']], function ($api) {
        $api->post('logout', 'App\Http\Controllers\Api\AuthenticateController@logout');
        //用户相关
        $api->get('get_info', 'App\Http\Controllers\Api\UserController@getInfo')->name('get_info');
        $api->get('users', 'App\Http\Controllers\Api\UserController@users')->name('users');
        $api->resource('users', 'App\Http\Controllers\Api\UserController', ['only' => ['store', 'update']]);
        $api->post('update_user_role/{id}', 'App\Http\Controllers\Api\UserController@updateUserRole');
        $api->post('uploadAvatar', 'App\Http\Controllers\Api\ImageController@uploadAvatar');
        //权限相关
        $api->resource('permissions', 'App\Http\Controllers\Api\PermissionsController');
        $api->get('all_permissions', 'App\Http\Controllers\Api\PermissionsController@allPermissions');
        //角色相关
        $api->resource('roles', 'App\Http\Controllers\Api\RoleController');
        $api->get('all_roles', 'App\Http\Controllers\Api\RoleController@allRoles');
        //系统字典相关
        $api->get('all_dic_types', 'App\Http\Controllers\Api\DictionaryTypeController@allDicTypes');
        $api->resource('dic_types', 'App\Http\Controllers\Api\DictionaryTypeController', ['only' => ['index', 'store', 'update']]);
        $api->resource('dic_items', 'App\Http\Controllers\Api\DictionaryItemController', ['only' => ['index', 'store', 'update']]);
        $api->get('toggle_dic_items/{id}', 'App\Http\Controllers\Api\DictionaryItemController@toggleDicItem');
        //messages
        $api->resource('messages', 'App\Http\Controllers\Api\MessageController');
        $api->get('message/count', 'App\Http\Controllers\Api\MessageController@count');
        //部门相关路由
        $api->resource('branches', 'App\Http\Controllers\Api\BranchController');
    });


    $api->get('users/{id}', 'App\Http\Controllers\Api\UserController@getUser');

    $api->post('login', 'App\Http\Controllers\Api\AuthenticateController@login');

    $api->post('refresh', 'App\Http\Controllers\Api\AuthenticateController@refresh');
});

$api->version('v2', function ($api) {
    $api->get('version', function () {
        return response('this is version v2');
    });
});
