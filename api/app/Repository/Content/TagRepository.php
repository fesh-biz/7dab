<?php

namespace App\Repository\Content;

use App\Models\Content\Tag;

class TagRepository
{
    protected Tag $model;
    
    public function __construct(Tag $model)
    {
        $this->model = $model;
    }
    
    public function getModel(): Tag
    {
        return $this->model;
    }
}