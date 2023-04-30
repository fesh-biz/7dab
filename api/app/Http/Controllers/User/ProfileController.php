<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\Content\PostRepository;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected UserService $service;
    
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }
    
    public function contentStats(): JsonResponse
    {
        $contentStats = $this->service->getContentStats(auth()->id());
        
        return response()->json($contentStats);
    }
    
    public function uploadAvatar(Request $r): JsonResponse
    {
        $r->validate([
            'avatar' => 'required|file|mimes:jpeg|max:200',
        ]);
    
        $user = $this->service->uploadAvatar($r->avatar);
        
        return response()->json($user);
    }
    
    public function posts(Request $r): JsonResponse
    {
        $keyword = $r->keyword;

        $status = null;
        if (in_array($r->status, ['approved', 'draft', 'pending'])) {
            $status = $r->status;
        }

        $postRepo = app()->make(PostRepository::class);

        $posts = $postRepo->getProfilePaginatedPosts($status, $keyword);

        return response()->json($posts);
    }
}
