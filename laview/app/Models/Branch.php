<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    public function dept()
    {
        return $this->belongsTo(self::class,'parent_id');
    }
}
