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
        $api->post('save_error_logger', 'App\Http\Controllers\Api\ErrorLoggerController@store');
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
        $api->get('children_permissions/{id}', 'App\Http\Controllers\Api\PermissionsController@children');
        //角色相关
        $api->resource('roles', 'App\Http\Controllers\Api\RoleController');
        $api->get('all_roles', 'App\Http\Controllers\Api\RoleController@allRoles');
        $api->get('permissionByRoleId/{id}', 'App\Http\Controllers\Api\RoleController@permissionByRoleId');
        $api->post('updRolePermission/{id}', 'App\Http\Controllers\Api\RoleController@updRolePermission');
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

        //计划相关路由
        $api->resource('schedules', 'App\Http\Controllers\Api\ScheduleController');
        $api->post('get_schedules', 'App\Http\Controllers\Api\ScheduleController@getSchedules');
        $api->post('upd_schedule_time/{id}', 'App\Http\Controllers\Api\ScheduleController@updTimeRange');
        $api->resource('calendars', 'App\Http\Controllers\Api\CalendarController');

        //表单编辑器相关路由
        $api->resource('forms', 'App\Http\Controllers\Api\FormController');
        $api->post('form_data/{id}/{version}', 'App\Http\Controllers\Api\FormDataController@store');
        $api->get('form_data/{id}', 'App\Http\Controllers\Api\FormDataController@show');
        $api->get('toggle_form/{id}', 'App\Http\Controllers\Api\FormController@toggle');

        //标签相关路由
        $api->resource('tags', 'App\Http\Controllers\Api\TagController', ['only' => ['index', 'store', 'update']]);
        $api->get('searchTags/{query?}', 'App\Http\Controllers\Api\TagController@search');
        //分类目录相关路由
        $api->resource('categories', 'App\Http\Controllers\Api\CategoryController');
        $api->get('children_categories/{id}', 'App\Http\Controllers\Api\CategoryController@children');
        $api->get('categoryTree/{id?}', 'App\Http\Controllers\Api\CategoryController@all');
        //文章相关路由
        $api->resource('articles', 'App\Http\Controllers\Api\ArticleController');
        //文件上传相关路由
        $api->post('uploadImg', 'App\Http\Controllers\Api\AttachmentController@uploadImg');
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
