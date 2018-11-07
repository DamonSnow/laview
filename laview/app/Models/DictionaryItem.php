<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DictionaryItem extends Model
{
    protected $fillable = ['type_id', 'item_name', 'item_value', 'enable', 'sort', 'comment'];

    public function dicType()
    {
        return $this->belongsTo(DictionaryType::class, 'type_id');
    }
}
