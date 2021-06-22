<?php

namespace App\Repository\Content;

use App\Models\Content\Post;
use Illuminate\Pagination\LengthAwarePaginator;

class PostRepository
{
    public function getPaginatedPosts(): LengthAwarePaginator
    {
        return Post::with([
            'tags:id,title',
            'user:id,name',
            'postImages',
            'postTexts'
        ])->paginate(10);
    }
}
