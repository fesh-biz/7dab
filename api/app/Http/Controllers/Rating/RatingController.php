<?php

namespace App\Http\Controllers\Rating;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rating\RatingRequest;
use App\Services\Rating\RatingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    protected RatingService $service;
    
    public function __construct(RatingService $service)
    {
        $this->service = $service;
    }
    
    public function vote(RatingRequest $r): JsonResponse
    {
        $ratingVote = $this->service->vote($r->id, $r->type, $r->is_upvote);
        
        return response()->json($ratingVote);
    }
}
