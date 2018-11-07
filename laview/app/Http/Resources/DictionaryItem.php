<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DictionaryItem extends JsonResource
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
            'type_id'    => $this->type_id,
            'dic_type'   => $this->dicType->dic_name,
            'item_name'  => $this->item_name,
            'item_value' => $this->item_value,
            'enable'     => $this->enable,
            'sort'       => $this->sort,
            'comment'    => $this->comment,
            'created_at' => $this->created_at,
        ];

    }
}
