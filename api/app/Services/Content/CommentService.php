<?php

namespace App\Services\Content;

use App\Models\Content\Comment;
use App\Models\Content\Post;
use App\Repositories\Content\CommentRepository;
use App\Repositories\Content\PostRepository;
use DB;
use Illuminate\Support\Collection;

class CommentService
{
    protected CommentRepository $repo;
    protected PostRepository $postRepository;
    
    public function __construct(CommentRepository $repo, PostRepository $postRepository)
    {
        $this->repo = $repo;
        $this->postRepository = $postRepository;
    }
    
    public function getModel(): Comment
    {
        return $this->repo->getModel();
    }
    
    public function createWithIncrementingPostCommentsCounter(
        int $commentableId,
        int $postId,
        string $commentableType,
        string $body
    ): Comment
    {
        DB::beginTransaction();
        $comment = $this->repo->create([
            'user_id' => auth('api')->id(),
            'commentable_id' => $commentableId,
            'commentable_type' => $this->getCommentableModel($commentableType),
            'body' => $body,
            'post_id' => $postId
        ]);
        
        $comment->rating()->create();
        
        $comment->load('user', 'rating');
        
        $this->postRepository->incrementCommentsCounter($postId);
        DB::commit();
        
        return $comment;
    }
    
    private function getCommentableModel(string $type): ?string
    {
        $types = [
            'post' => Post::class,
            'comment' => Comment::class
        ];
        
        return $types[$type] ?? null;
    }
}