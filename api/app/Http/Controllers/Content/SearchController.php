<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Services\Content\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected PostService $postService;
    
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }
    
    public function index (Request $r): JsonResponse
    {
        return $this->sendPaginationResponse(
            $this->postService
                ->getPaginatedPosts(true, ['tagsIds' => $r->tids, 'title' => $r->kw])
        );
    }
}
