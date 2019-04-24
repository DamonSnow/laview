<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title', 'keywords', 'description', 'cover_image', 'content', 'view_count', 'vote_count', 'comment_count', 'collection_count',
        'enable', 'recommend', 'top', 'weight', 'access_type', 'access_value', 'created_year', 'created_month', 'category_id', 'slug', 'user_id', 'category_id',
    ];
}
