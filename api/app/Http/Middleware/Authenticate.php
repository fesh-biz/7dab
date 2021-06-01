<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\JsonResponse;

class Authenticate extends Middleware
{
    protected function redirectTo($request): JsonResponse
    {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }
}
