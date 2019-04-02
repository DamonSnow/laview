<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $fillable = ['name', 'color', 'bg_color', 'drag_bg_color', 'border_color'];
}
