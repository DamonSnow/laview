<?php

namespace App\Http\Controllers\Api;

use App\Models\FormData;
use Illuminate\Http\Request;

class FormDataController extends ApiController
{
    public function show($id)
    {
        $data = FormData::where('form_id',$id)->get();
        $form = [];
        foreach ($data as $item) {
            array_push($form,unserialize($item->item_data));
        }
        return $this->success($form);
    }

    public function store($id, Request $request)
    {
//        var_dump($request->input('data'));
        try {
            $formData = FormData::create([
                'form_id' => $id,
                'item_data' => serialize($request->input('data'))
            ]);
            return $this->success($formData, 'success');
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }
    }
}
