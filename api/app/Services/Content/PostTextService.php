<?php

namespace App\Services\Content;

use App\Models\Content\PostText;
use App\Repository\Content\PostTextRepository;

class PostTextService
{
    protected PostTextRepository $repo;

    public function __construct(PostTextRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getModel(): PostText
    {
        return $this->repo->getModel();
    }

    public function create(int $postId, int $order, string $body): PostText
    {
        return $this->repo->create($postId, $order, $body);
    }
}
