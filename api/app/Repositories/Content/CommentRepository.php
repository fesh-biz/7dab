<?php

namespace App\Repositories\Content;

use App\Models\Content\Comment;
use phpDocumentor\Reflection\Types\Boolean;

class CommentRepository
{
    protected Comment $model;
    
    public function __construct(Comment $model)
    {
        $this->model = $model;
    }
    
    public function getModel(): Comment
    {
        return $this->model;
    }
    
    public function create(int $commentableId, string $commentableType, string $body): Comment
    {
        return $this->model->create([
            'commentable_id' => $commentableId,
            'commentable_type' => $commentableType,
            'body' => $body
        ]);
    }
    
    public function update(Comment $comment, string $body): Boolean
    {
        return $comment->update([
            'body' => $body
        ]);
    }
}