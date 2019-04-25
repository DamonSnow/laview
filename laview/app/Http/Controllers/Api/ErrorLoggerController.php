<?php

namespace App\Http\Controllers\Api;

use App\Models\ErrorLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ErrorLoggerController extends ApiController
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'mes' => 'required',
            'type' => 'required',
            'url' => 'required',
        ]);
        if ($validator->fails()) {
            return failed_response($validator->errors()->toArray(), 'error', 1001);
        }
        try {
            ErrorLogger::create([
                'code'       => $request->input('code'),
                'mes'  => $request->input('mes'),
                'type'       => $request->input('type'),
                'url' => $request->input('url'),
            ]);
            return $this->success('error logged', 'success');
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }
    }
}
