<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Http\Resources\Tag AS TagResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class TagController extends ApiController
{
    public function index()
    {
        $tag = new Tag();
        return TagResource::collection($tag->paginate(Input::get('size') ?: 20));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slug' => 'required|unique:tags',
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return failed_response($validator->errors()->toArray(), 'error', 1001);
        }
        DB::beginTransaction();
        try {
            $tag = Tag::create($request->all());
            DB::commit();
            return $this->success($tag, 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slug' => 'required|unique:tags',
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return failed_response($validator->errors()->toArray(), 'error', 1001);
        }
        DB::beginTransaction();
        try {
            $tag = Tag::find($id);
            $tag->update($request->all());
            DB::commit();
            return $this->success('update', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }
    }
}
