<?php
/**
 * Created by PhpStorm.
 * User: Damon
 * Date: 2018/10/26
 * Time: 10:13
 */
if(!function_exists('failed_response')) {
    function failed_response($data, $status ,$code, $header = []) {
        return \Illuminate\Support\Facades\Response::json(['message' => $data, 'status' => $status, 'code' => $code],400);
    }
}

if(!function_exists('response')) {
    function response($data, $status ,$code, $header = []) {
        return \Illuminate\Support\Facades\Response::json(['message' => $data, 'status' => $status, 'code' => $code],200);
    }
}