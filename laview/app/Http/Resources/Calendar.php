<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Calendar extends JsonResource
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
            'id'         => $this->id,
            'name'    => $this->name,
            'color'   => $this->color,
            'bgColor'  => $this->bg_color,
            'dragBgColor' => $this->drag_bg_color,
            'borderColor'     => $this->border_color,
        ];
    }
}
