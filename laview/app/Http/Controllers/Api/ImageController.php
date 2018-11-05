<?php

namespace App\Http\Controllers\Api;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\ImgRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ImageController extends ApiController
{
    public function uploadAvatar(Request $request,ImageUploadHandler $uploader)
    {
        $user = auth('api')->id();
        $result = $uploader->save(Input::file('avatar'),'avatars',$user, 362);
        
        return $this->success($result, 'success');
//        if($result) {
////            $data['avatar'] = $result['path'];
//
//        }
    }
}
