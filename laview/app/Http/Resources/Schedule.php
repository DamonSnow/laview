<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Schedule extends JsonResource
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
            'calendarId' => $this->calendar_id,
            'title'      => $this->title,
            'body'       => $this->body,
            'start'      => $this->start_at,
            'end'        => $this->end_at,
            'category'   => $this->catg,
            'isAllDay'   => $this->is_all_day,
            'isReadOnly' => $this->is_readonly,
            'isPrivate'  => $this->is_private,
            'isVisible'  => $this->is_visible,
            'state'      => $this->status,
            'backgroundColor'        => $this->calendar->bg_color,
            'borderColor'        => $this->calendar->border_color,
        ];
    }
}
