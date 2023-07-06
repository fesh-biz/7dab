<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserRepository $repo;
    
    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }
    
    public function index(Request $r): JsonResponse
    {
        ddh($r);
        
        return response()->json(
            // $this->repo->getPaginatedRecordsForAdmin($search)
        );
    }

    public function fakeUsers(): JsonResponse
    {
        $users = User::where('email', 'like', '%terevenky.com%')->get();
        
        return response()->json($users);
    }
}
