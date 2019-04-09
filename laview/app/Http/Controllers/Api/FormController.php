<?php

namespace App\Http\Controllers\Api;

use App\Models\Form;
use App\Http\Resources\Form AS FormResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class FormController extends ApiController
{
    public function index()
    {
        $form = new Form();
        return FormResource::collection($form->paginate(Input::get('size') ?: 20));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'form_name' => 'required',
            'form_code' => 'required|unique:forms',
        ]);
        if ($validator->fails()) {
            return failed_response($validator->errors()->toArray(), 'error', 1101);
        }
        DB::beginTransaction();
        try {
            $form = Form::create($request->all());
            DB::commit();
            return $this->success($form, 'success');
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
            'form_name' => 'required',
        ]);
        if ($validator->fails()) {
            return failed_response($validator->errors()->toArray(), 'error', 1102);
        }
        DB::beginTransaction();
        try {
            $form = Form::find($id);
            $form->update($request->all());
            DB::commit();
            return $this->success('update','success');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }
    }

    public function toggle($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $form = Form::find($id);
            $form->update(['enable' => $request->enable]);
            DB::commit();
            return $this->success('toggle form enable success','success');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }
    }
}
