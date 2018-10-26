<?php
/**
 * Created by PhpStorm.
 * User: Damon
 * Date: 2018/10/25
 * Time: 14:49
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Resources\Permission as PermissionResource;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionsController extends ApiController
{
//    public function __construct()
//    {
//        $this->middleware(['auth:api','cors']);
//    }

    public function index()
    {
        $permissions = new Permission();
        return PermissionResource::collection($permissions->paginate(Input::get('size') ?: 20));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required',
        ]);
        if ($validator->fails()) {
            return failed_response($validator->errors()->toArray(),'error',1001);
        }
        try {
            $permission = Permission::create(['name' => $request->input('name'),'comment' => $request->input('comment')]);
            return $this->success($permission,'success');
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }
    }

    public function allPermissions()
    {
        $permissions = new Permission();
        return PermissionResource::collection($permissions->all());
    }
}