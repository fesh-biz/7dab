<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PostController extends Controller
{
    public function index(): Collection
    {
        return Post::with([
            'user:id,name,rating',
            'tags:id,title,slug,body'
        ])->get();
    }
}
