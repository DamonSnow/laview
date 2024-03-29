<?php

namespace App\Http\Controllers\Api;

use App\Models\DictionaryItem;
use App\Http\Resources\DictionaryItem AS DictionaryItemResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class DictionaryItemController extends ApiController
{
    public function index()
    {
        $dicItems = new DictionaryItem();
        return DictionaryItemResource::collection($dicItems->paginate(Input::get('size') ?: 20));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_id' => 'required',
            'item_name' => 'required|unique:dictionary_items',
            'item_value' => 'required',
            'sort' => 'required',
        ]);
        if ($validator->fails()) {
            return failed_response($validator->errors()->toArray(), 'error', 1001);
        }
        DB::beginTransaction();
        try {
            $dicItem = DictionaryItem::create($request->all());
            DB::commit();
            return $this->success($dicItem, 'success');
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
            'type_id' => 'required',
            'item_value' => 'required',
            'sort' => 'required',
        ]);
        if ($validator->fails()) {
            return failed_response($validator->errors()->toArray(), 'error', 1001);
        }
        DB::beginTransaction();
        try {
            $dicItem = DictionaryItem::find($id);
            $dicItem->update($request->all());
            DB::commit();
            return $this->success('update','success');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }
    }

    public function toggleDicItem($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $dicItem = DictionaryItem::find($id);
            $dicItem->update(['enable' => $request->enable]);
            DB::commit();
            return $this->success('toggle dictionary item enable','success');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }
    }
}
