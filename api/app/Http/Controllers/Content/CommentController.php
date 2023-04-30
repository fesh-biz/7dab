<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\CommentRequest;
use App\Models\Content\Comment;
use App\Repositories\Content\CommentRepository;
use App\Services\Content\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected CommentService $service;
    protected CommentRepository $repo;
    
    public function __construct(CommentService $service, CommentRepository $repo)
    {
        $this->service = $service;
        $this->repo = $repo;
    }
    
    public function store(CommentRequest $r): JsonResponse
    {
        return response()->json(
            $this->service->createWithIncrementingPostCommentsCounter(
                $r->commentable_id,
                $r->post_id,
                $r->commentable_type,
                $r->body
            )
        );
    }
    
    // Non refactored
    
    public function update(CommentRequest $r, int $id): JsonResponse
    {
        /** @var Comment $comment */
        $comment = $this->service->getModel()
            ->with('answers')
            ->findOrFail($id);
        
        $user = auth('api')->user();
        if ($comment->user_id !== $user->id || $user->role_id > 2) {
            abort(401);
        }
        
        if ($comment->answers->count() || $user->role_id > 2) {
            abort(422, trans('can_no_longer_be_edited'));
        }
        
        return response()->json(
            $this->repo->update($comment, $r->body)
        );
    }
}
