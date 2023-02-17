<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Services\Content\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected CommentService $service;
    
    public function __construct(CommentService $service)
    {
        $this->service = $service;
    }
    
    public function comments(Request $r): JsonResponse
    {
        return response()->json($this->service->getComments($r->commentable_type, $r->commentable_id));
    }
}
