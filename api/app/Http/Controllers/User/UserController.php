<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Content\PostService;
use App\Services\User\UserService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $service;
    
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }
    
    public function find(int $id): JsonResponse
    {
        return response()->json($this->service->getModel()->find($id));
    }
    
    public function me(): Authenticatable
    {
        return auth()->user();
    }
    
    public function stats(int $id): JsonResponse
    {
        $stats = $this->service->getStats($id);
        
        return response()->json($stats);
    }
    
    public function posts(int $userId): JsonResponse
    {
        $postService = app()->make(PostService::class);
        $posts = $postService->getPaginatedPostsBySearch(['user_id' => $userId]);
        
        return $this->sendPaginationResponse($posts);
    }
}
