<?php

namespace App\Http\Controllers\Api;

use App\Handlers\MyTree;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Resources\Branch AS BranchResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BranchController extends ApiController
{
    public function index()
    {
        $data = Branch::all();
        if ($data->count() > 0) {
            $branches = MyTree::makeTree($data, [
                'expanded' => true,
            ]);
        } else {
            $branches[0] = '';
        }

        return $this->success($branches[0]);
    }

    /**
     * @SWG\Post(
     *     path="/api/branches",
     *     summary="部门管理",
     *     tags={"新增部门"},
     *     description="新增部门",
     *     operationId="branches.store",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="label",
     *         in="query",
     *         description="部门名称",
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="code",
     *         in="query",
     *         description="部门编码",
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="parent_id",
     *         in="query",
     *         description="父级部门id",
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="新增部门成功",
     *         @SWG\Schema(
     *            type="json",
     *            @SWG\Property(
     *               property="code",
     *               type="integer",
     *               description="状态码"
     *            ),
     *             @SWG\Property(
     *               property="data",
     *               type="Object",
     *               description="数据"
     *            ),
     *             @SWG\Property(
     *               property="msg",
     *               type="string",
     *               description="信息"
     *            )
     *         )
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="error",
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'label'     => 'required',
            'code'      => 'required',
            'parent_id' => 'required',
        ]);
        if ($validator->fails()) {
            return failed_response($validator->errors()->toArray(), 'error', 1002);
        }
        try {
            $branch = Branch::create(['label' => $request->input('label'), 'code' => $request->input('code'), 'parent_id' => $request->input('parent_id')]);
            $branch->expand = 'expand';
            return $this->success($branch, 'success');
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'label'     => 'required',
            'code'      => 'required',
            'parent_id' => 'required',
        ]);
        if ($validator->fails()) {
            return failed_response($validator->errors()->toArray(), 'error', 1001);
        }
        DB::beginTransaction();
        try {
            $branch = Branch::find($id);
            if ($branch->label != $request->input('label')) $branch->label = $request->input('label');
            if ($branch->code != $request->input('code')) $branch->code = $request->input('code');
            if ($branch->parent_id != $request->input('parent_id')) $branch->parent_id = $request->input('parent_id');
            $branch->save();
            DB::commit();
            return $this->success('update', 'success');
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
            $branch = Branch::findOrFail($id);
            $parentId = $branch->parent_id;
            $branch->delete();
            if ($parentId > 0)
                Branch::where('parent_id', $id)->update(['parent_id' => $parentId]);
            else
                throw new \Exception('该节点为根节点无法删除', 2001);
            DB::commit();
            return $this->success('delete success', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $code = $e->getCode();
            return success_response($msg, 'error', $code);
        }
    }

}
