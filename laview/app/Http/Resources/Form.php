<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Form extends JsonResource
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
            'id'         => $this->id,
            'form_code'  => $this->form_code,
            'form_name'  => $this->form_name,
            'form_model' => $this->form_model,
            'enable'     => $this->enable,
            'version'    => $this->version,
            'comment'    => $this->comment,
            'created_at' => $this->created_at,
        ];
    }
}
