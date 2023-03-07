<?php

namespace App\Repositories\Content;

use App\Models\Content\Post;
use Illuminate\Pagination\LengthAwarePaginator;
use phpDocumentor\Reflection\Types\Boolean;

class PostRepository
{
    protected Post $model;
    
    public function __construct(Post $model)
    {
        $this->model = $model;
    }
    
    public function incrementComments(int $id): int
    {
        return $this->model->whereId($id)->increment('comments');
    }
    
    public function getPaginatedPosts(
        array $tagsIds = null,
        string $keyword = null
    ): LengthAwarePaginator
    {
        $query = $this->model
            ->whereStatus('approved')
            ->orderBy('id', 'desc');
        
        if ($tagsIds) {
            $query = $query->whereHas('tags', function ($q) use ($tagsIds) {
                $q->whereIn('id', $tagsIds);
            }, '=', count($tagsIds));
        }
        
        if ($keyword) {
            $query = $query->where('title', 'like', "%$keyword%");
        }
        
        if (auth('api')->user()) {
            $query->with('myVote');
        }
        
        return $query->withTagsAuthorContent()
            ->paginate(10);
    }
    
    public function incrementViewsMultiple(array $ids)
    {
        $this->model->whereIn('id', $ids)
            ->increment('views');
    }
    
    public function incrementViews(int $id)
    {
        $this->model->where('id', $id)
            ->increment('views');
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
