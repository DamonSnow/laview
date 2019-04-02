<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['calendar_id', 'title', 'body', 'start_at', 'end_at', 'is_all_day', 'catg', 'is_readonly', 'is_visible', 'is_private', 'status'];

    public function calendar()
    {
        return $this->belongsTo('App\Models\Calendar','calendar_id','id');
    }

    public function getCatgAttribute($value)
    {
        switch ($value) {
            case 0:
                $str = 'time';
                break;
            case 1:
                $str = 'allday';
                break;
            case 2:
                $str = 'task';
                break;
            case 3:
                $str = 'milestone';
                break;
            default:
                $str = 'time';
        }
        return $str;
    }
}
