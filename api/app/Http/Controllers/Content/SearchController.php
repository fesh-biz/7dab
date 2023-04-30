<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Services\Content\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function posts (Request $r): JsonResponse
    {
        $search = [
            'tags_ids' => $r->tids,
            'keyword' => $r->kw
        ];
        
        $postService = app()->make(PostService::class);
        
        $posts = $postService->getPaginatedPostsBySearch($search);
        
        return $this->sendPaginationResponse($posts);
    }
}
