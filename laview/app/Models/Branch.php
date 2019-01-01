<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable= ['label','parent_id','code'];

    public function dept()
    {
        return $this->belongsTo(self::class,'parent_id');
    }
}
