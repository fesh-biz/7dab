<?php

namespace App\Repositories\Content;

use App\Models\Content\Comment;

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
}