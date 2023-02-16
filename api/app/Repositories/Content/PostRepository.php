<?php

namespace App\Repositories\Content;

use App\Models\Content\Post;
use Illuminate\Pagination\LengthAwarePaginator;

class PostRepository
{
    protected Post $model;

    public function __construct(Post $model)
    {
        $this->model = $model;
    }

    public function getPaginatedPosts(): LengthAwarePaginator
    {
        return $this->model->withTagsAuthorContent()
            ->whereStatus('approved')
            ->orderBy('id', 'desc')
            ->paginate(10);
    }

    public function find(int $postId): ?Post
    {
        return $this->model->findOrFail($postId);
    }

    public function findWithBasicRelationships(int $postId): ?Post
    {
        return $this->model->withTagsAuthorContent()
            ->findOrFail($postId);
    }

    public function create(string $title): Post
    {
        $data = [
            'title' => $title,
            'user_id' => auth()->id()
        ];
        
        if (auth()->user()->roleName === 'admin') {
            $data['status'] = 'approved';
        }
        
        return $this->model->create($data);
    }
}
