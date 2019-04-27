<?php
/**
 * Created by PhpStorm.
 * User: Damon
 * Date: 2018/10/25
 * Time: 14:49
 */

namespace App\Http\Controllers\Api;


use App\Handlers\MyTree;
use Dingo\Api\Auth\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\Role as RoleResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


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
        DB::beginTransaction();
        try {
            $permissions = $request['permissions'];
            $role = Role::create(['name' => $request->input('name'), 'guard_name' => 'api', 'comment' => $request->input('comment')]);
//            foreach ($permissions as $permission) {
//                $p = Permission::where('id', '=', $permission)->firstOrFail();
//                // 获取新创建的角色并分配权限
//                $role = Role::where('name', '=', $request['name'])->first();
//                $role->givePermissionTo($p);
//            }
            DB::commit();
            return $this->success($role, 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }
    }

    public function update($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $role = Role::find($id);
            if ($role->name != $request->input('name')) $role->name = $request->input('name');
            if ($role->comment != $request->input('comment')) $role->comment = $request->input('comment');
//            $role->syncPermissions([]);
//            foreach ($request->input('permissions') as $permission) {
//                $p = Permission::where('id', '=', $permission)->firstOrFail(); //从数据库中获取相应权限
//                $role->givePermissionTo($p);  // 分配权限到角色
//            }
            $role->save();
            DB::commit();
            return $this->success('角色信息更新成功', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $role = Role::find($id);
            $role->delete();
            $role->syncPermissions([]);
            DB::commit();
            return $this->success('角色删除成功', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }
    }

    public function allRoles()
    {
        $roles = Role::all()->pluck('name', 'id')->toArray();
        return $this->success($roles, 'success');
    }

    public function permissionByRoleId($id)
    {
        $roles = Role::with('permissions')->where('id', '=', $id)->get();
        foreach ($roles as $role) {
            $permissionIds = $role->permissions->pluck('id')->toArray();
        }

        $permissionData = Permission::all()->reduce(function ($arr, $item) use ($permissionIds) {
            $arr[] = [
                'id' => $item->id,
                'title' => $item->name,
                'parent_id' => $item->parent_id,
                'checked' => in_array($item->id, $permissionIds) ? true : false
            ];
            return $arr;
        }, []);
        $data = [];
        if(!empty($permissionData)) {
            $data = MyTree::makeTree($permissionData,array(
                'expanded' => true
            ));
        }

        return $this->success($data, 'success');
    }

    public function updRolePermission($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $role = Role::find($id);
            $role->syncPermissions([]);
            foreach ($request->input('permissions') as $permission) {
                $p = Permission::where('id', '=', $permission)->firstOrFail(); //从数据库中获取相应权限
                $role->givePermissionTo($p);  // 分配权限到角色
            }
            DB::commit();
            return $this->success('角色权限更新成功', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }
    }
}