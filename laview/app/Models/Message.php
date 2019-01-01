<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['to_user', 'title', 'content', 'status'];

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user');
    }

    public function FromUser()
    {
        return $this->belongsTo(User::class, 'from_user');
    }
}
