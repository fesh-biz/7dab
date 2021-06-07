<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $r)
    {
        $offset = intval($r->offset);

        if ($offset >= Post::count()){
            return response()->json([]);
        }

        return Post::with([
            'user:id,name,rating',
            'tags:id,title,slug,body'
        ])->limit(10)->offset($offset)->get();
    }
}
