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
    
    public function isExists(string $title): bool
    {
        $slug = \Str::slug($title);
        return $this->model->whereSlug($slug)->exists();
    }
    
    public function create(string $title, string $body = null): Tag
    {
        return $this->model->create([
            'title' => $title,
            'body' => $body,
            'user_id' => auth()->id()
        ]);
    }
}