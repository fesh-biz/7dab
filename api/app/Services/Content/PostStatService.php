<?php

namespace App\Services\Content;

use App\Models\Content\PostStat;
use App\Repository\Content\PostStatRepository;

class PostStatService
{
    protected PostStatRepository $repo;

    public function __construct(PostStatRepository $repo)
    {
        $this->repo = $repo;
    }

    public function create(int $postId): PostStat
    {
        return $this->repo->create($postId);
    }
}
