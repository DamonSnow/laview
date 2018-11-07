<?php

namespace App\Http\Controllers\Api;

use App\Models\DictionaryType;
use App\Http\Resources\DictionaryType AS DictionaryTypeResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class DictionaryTypeController extends ApiController
{
    public function index()
    {
        $dicTypes = new DictionaryType();
        return DictionaryTypeResource::collection($dicTypes->paginate(Input::get('size') ?: 20));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dic_code' => 'required|unique:dictionary_types',
            'dic_name' => 'required',
        ]);
        if ($validator->fails()) {
            return failed_response($validator->errors()->toArray(), 'error', 1001);
        }
        DB::beginTransaction();
        try {
            $role = DictionaryType::create($request->all());
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
        $validator = Validator::make($request->all(), [
            'dic_name' => 'required',
        ]);
        if ($validator->fails()) {
            return failed_response($validator->errors()->toArray(), 'error', 1001);
        }
        DB::beginTransaction();
        try {
            $user = DictionaryType::find($id);
            $user->update($request->all());
            DB::commit();
            return $this->success('update', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }
    }

    public function allDicTypes()
    {
        return $this->success(DictionaryType::all()->pluck('dic_name', 'id')->toArray(), 'success');
    }
}
