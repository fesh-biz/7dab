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
    
    public function create(array $data): Comment
    {
        return $this->model->create($data);
    }
    
    public function update(Comment $comment, string $body): Boolean
    {
        return $comment->update([
            'body' => $body
        ]);
    }
}