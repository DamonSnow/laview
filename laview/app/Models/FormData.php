<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormData extends Model
{
    protected $fillable = ['form_id', 'item_data'];
    protected $table = 'form_data';
}
