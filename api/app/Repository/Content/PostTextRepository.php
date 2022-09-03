<?php

namespace App\Repository\Content;

use App\Models\Content\PostText;

class PostTextRepository
{
    protected PostText $repo;

    public function __construct(PostText $repo)
    {
        $this->repo = $repo;
    }

    public function create(int $postId, int $order, string $body): PostText
    {
        return PostText::create([
            'post_id' => $postId,
            'order' => $order,
            'body' => $body
        ]);
    }
}
