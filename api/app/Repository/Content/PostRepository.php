<?php

namespace App\Repository\Content;

use App\Models\Content\Post;
use Illuminate\Pagination\LengthAwarePaginator;

class PostRepository
{
    public function getPaginatedPosts(): LengthAwarePaginator
    {
        return Post::withTagsAuthorContent()
            ->paginate(10);
    }

    public function findPost(int $postId):? Post
    {
        return Post::withTagsAuthorContent()
            ->find($postId);
    }
}
