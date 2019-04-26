<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Handlers\ImageUploadHandler;
use Illuminate\Support\Facades\Input;

class AttachmentController extends ApiController
{
    public function uploadImg(Request $request,ImageUploadHandler $uploader)
    {
        $user = auth('api')->id();
        $result = $uploader->save(Input::file('image'),'articles',$user, 1080);

        if($result) {
            return $this->success($result, 'success');
        }
    }
}
