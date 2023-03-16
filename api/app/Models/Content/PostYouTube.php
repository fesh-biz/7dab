<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostYouTube extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'post_id',
        'order',
        'youtube_id',
        'title',
    ];
}
