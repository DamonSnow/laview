<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLogger extends Model
{
    protected $fillable = ['code', 'mes', 'type', 'url'];
}
