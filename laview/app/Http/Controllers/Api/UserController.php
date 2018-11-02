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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

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

    public function  store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|exists:users,email',
            'job_number' => 'required|exists:users',
        ]);
        if ($validator->fails()) {
            return failed_response($validator->errors()->toArray(),'error',400);
        }
        DB::beginTransaction();
        try {
            $data = [
                'name' => $request->name,
                'avatar' => empty($request->avatar) ? 'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/ZqM7iaP4CR.png?imageView2/1/w/200/h/200': $request->avatar,
                'email' => $request->email,
                'job_number' => $request->job_number,
                'phone' => $request->phone,
                'password' => bcrypt(str_random(10)),
                'remember_token' => str_random(16),
                'active' => 1
            ];
            $user = User::create($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }

    }
}