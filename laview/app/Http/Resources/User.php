<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'user_id'    => $this->id,
            'name'       => $this->name,
            'token'      => $this->remember_token,
            'avatar'     => $this->avatar,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'job_number' => $this->job_number,
            'active'     => $this->active,
            'created_at' => $this->created_at,
            'roles'      => $this->when($request->route()->getName() == 'users', $this->roles->pluck('name')),
            'access'     => $this->when($request->route()->getName() == 'get_info', $this->getAllPermissions()->pluck('name')),
        ];
    }
}
