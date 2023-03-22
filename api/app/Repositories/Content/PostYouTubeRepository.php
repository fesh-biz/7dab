<?php

namespace App\Repositories\Content;

use App\Models\Content\PostYouTube;

class PostYouTubeRepository
{
    protected PostYouTube $model;
    
    public function __construct(PostYouTube $model)
    {
        $this->model = $model;
    }
    
    public function getModel(): PostYouTube
    {
        return $this->model;
    }
    
    public function create (int $postId, array $data) {
        $data = array_merge(['post_id' => $postId], $data);
        
        $this->model
            ->create($data);
    }
    
    public function update (int $id, array $data) {
        $this->model
            ->update(['id' => $id], $data);
    }
}