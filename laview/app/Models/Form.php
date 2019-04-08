<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = ['form_name', 'form_code', 'form_model', 'enable', 'version', 'comment'];
    protected $table = 'forms';
}
