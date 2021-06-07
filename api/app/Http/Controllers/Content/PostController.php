<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\Post;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        $posts = Post::with([
            'user:id,name,rating',
            'tags:id,title,slug,body'
        ])->paginate(10);

        return $this->sendPaginationResponse($posts);
    }
}
