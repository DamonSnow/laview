<?php
/**
 * Created by PhpStorm.
 * User: Damon
 * Date: 2018/10/25
 * Time: 14:49
 */

namespace App\Http\Controllers\Api;

use App\Models\Role;
use Dingo\Api\Auth\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\Role as RoleResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;


class RoleController extends ApiController
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'cors']);
    }

    public function index()
    {
        $roles = new Role();
        return RoleResource::collection($roles->paginate(Input::get('size') ?: 20));
    }

    public function create()
    {
        return Permission::all();// 获取所有权限
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return failed_response($validator->errors()->toArray(), 'error', 1001);
        }
        try {
            DB::beginTransaction();
            $permissions = $request['permissions'];
            $role = Role::create(['name' => $request->input('name'), 'guard_name' => 'api', 'comment' => $request->input('comment')]);
            foreach ($permissions as $permission) {
                $p = Permission::where('id', '=', $permission)->firstOrFail();
                // 获取新创建的角色并分配权限
                $role = Role::where('name', '=', $request['name'])->first();
                $role->givePermissionTo($p);
            }
            DB::commit();
            return $this->success($role, 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }
    }
}