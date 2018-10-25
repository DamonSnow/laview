<?php
/**
 * Created by PhpStorm.
 * User: Damon
 * Date: 2018/10/25
 * Time: 14:51
 */

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class Permission extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'comment' => $this->comment,
            'created_at' => $this->created_at,
        ];
    }
}