<?php
/**
 * Created by PhpStorm.
 * User: hqfdo
 * Date: 2018/7/30
 * Time: 21:37
 */

namespace App\Http\Controllers\Api;


use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\Input;

class UserController extends ApiController
{
    public function getUser($id)
    {

        return $this->success(new UserResource(User::find(1)));
    }

    public function users()
    {
        return $this->success(UserResource::collection(User::paginate(Input::get('limit') ?: 20)));
    }
}