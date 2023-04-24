<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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
    
        $userData = $this->service->uploadAvatar($r->avatar);
        
        return response()->json('success');
    }
}
