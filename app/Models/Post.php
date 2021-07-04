<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'author',
        'created_at',
        'updated_at',
        'image',
        'likes',
        'like_user'
    ];
}
