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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class UserController extends ApiController
{
    public function __construct()
    {
        $this->middleware(['auth:api','cors'])->only([
            'getInfo'
        ]);
    }

    public function getUser($id)
    {

        return $this->success(new UserResource(User::find($id)));
    }

    public function users()
    {
        if(!empty(Input::get('jobNum'))) {
            switch (Input::get('jobNum')) {
                case 'desc':
                    $user = User::orderby('job_number','DESC');
                    break;
                case 'asc':
                    $user = User::orderby('job_number','ASC');
                    break;
                default:
                    $user = new User();
            }
        } else {
            $user = new User();
        }
        return UserResource::collection($user->paginate(Input::get('size') ?: 20));
    }

    public function getInfo()
    {
        return $this->success(new UserResource(User::find(auth('api')->id())));
    }
}