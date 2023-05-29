<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function fakeUsers(): JsonResponse
    {
        $users = User::where('email', 'like', '%terevenky.com%')->get();
        
        return response()->json($users);
    }
}
