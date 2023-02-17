<?php

namespace App\Services\Content;

use App\Models\Content\Comment;
use App\Models\Content\Post;
use App\Repositories\Content\CommentRepository;
use Illuminate\Support\Collection;

class CommentService
{
    protected CommentRepository $repo;
    
    public function __construct(CommentRepository $repo)
    {
        $this->repo = $repo;
    }
    
    public function getModel(): Comment
    {
        return $this->repo->getModel();
    }
    
    public function getComments(string $commentableType, int $commentableId): Collection
    {
        return $this->getModel()
            ->whereCommentableId($commentableId)
            ->whereCommentableType($this->getCommentableModel($commentableType))
            ->with('answers')
            ->with('author')
            ->get();
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