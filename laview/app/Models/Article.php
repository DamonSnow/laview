<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title', 'keywords', 'descriptions', 'cover_image', 'content', 'view_count', 'vote_count', 'comment_count', 'collection_count',
        'enable', 'recommend', 'top', 'weight', 'access_type', 'access_value', 'created_year', 'created_month', 'slug', 'user_id', 'publish_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag');
    }
}
