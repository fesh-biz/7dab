<?php

namespace App\Repository\Content;

use App\Models\Content\PostStat;

class PostStatRepository
{
    protected PostStat $model;

    public function __construct(PostStat $model)
    {
        $this->model = $model;
    }

    public function create (int $postId): PostStat
    {
        return $this->model->create([
            'post_id' => $postId
        ]);
    }
}
