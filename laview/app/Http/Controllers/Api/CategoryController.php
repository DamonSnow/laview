<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Category AS CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class CategoryController extends ApiController
{
    public function index()
    {
        $categories = Category::where('parent_id', '=', 0);
        return CategoryResource::collection($categories->paginate(Input::get('size') ?: 20));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required|unique:categories',
        ]);
        if ($validator->fails()) {
            return failed_response($validator->errors()->toArray(), 'error', 1001);
        }
        try {
            $category = Category::create([
                'name'       => $request->input('name'),
                'parent_id'  => $request->input('parent_id'),
                'code'       => $request->input('code'),
                'description' => $request->input('description'),
            ]);
            return $this->success($category, 'success');
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return failed_response($validator->errors()->toArray(), 'error', 1001);
        }
        DB::beginTransaction();
        try {
            $category = Category::find($id);
            if ($category->name != $request->input('name')) $category->name = $request->input('name');
            if ($category->code != $request->input('code')) $category->code = $request->input('code');
            if ($category->description != $request->input('description')) $category->description = $request->input('description');
            if ($category->parent_id != $request->input('parent_id')) $category->parent_id = $request->input('parent_id');
            $category->save();
            DB::commit();
            return $this->success('update', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }

    }

    public function children($id)
    {
        $categories = Category::where('parent_id', '=', $id)->get();
        return CategoryResource::collection($categories);
    }


}
