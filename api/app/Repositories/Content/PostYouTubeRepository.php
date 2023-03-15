<?php

namespace App\Repositories\Content;

use App\Models\Content\PostYouTube;

class PostYouTubeRepository
{
    protected PostYouTube $model;
    
    public function __construct()
    {
        $this->model = new PostYouTube();
    }
    
    public function getModel(): PostYouTube
    {
        return $this->model;
    }
}