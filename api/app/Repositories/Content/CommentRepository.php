<?php

namespace App\Repositories\Content;

use App\Models\Content\Comment;
use App\Models\Content\Post;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Boolean;

class CommentRepository
{
    protected Comment $model;
    
    public function __construct(Comment $model)
    {
        $this->model = $model;
    }
    
    public function getModel(): Comment
    {
        return $this->model;
    }
    
    public function getPostComments(int $postId): Collection
    {
        $query = $this->getModel()
            ->whereCommentableId($postId)
            ->whereCommentableType(Post::class)
            ->with(['answers', 'rating', 'user']);
        
        if (auth('api')->user()) {
            $query->with('myVote');
        }
        
        return $query->get();
    }
    
    // Non Refactored
    
    public function getPaginatedUserCommentsWithPostsAndParents(int $userId): LengthAwarePaginator
    {
        return $this->model->whereUserId($userId)
            ->with(['post', 'commentable', 'rating'])
            ->withCount('cleanAnswers')
            ->orderBy('id', 'desc')
            ->paginate(10);
    }
    
    public function getPaginatedAnswersOnUserWithPostAndParents(int $userId): LengthAwarePaginator
    {
        return $this->model->whereHas('commentable', function ($q) use ($userId) {
            $q->where('user_id', '=', $userId);
        })
            ->with(['post', 'commentable', 'rating', 'user'])
            ->orderBy('id', 'desc')
            ->paginate(10);
    }
    
    public function create(array $data): Comment
    {
        return $this->model->create($data);
    }
    
    public function update(Comment $comment, string $body): Boolean
    {
        return $comment->update([
            'body' => $body
        ]);
    }
    
    public function getUserCommentsIds(int $userId): array
    {
        return $this->model->whereUserId($userId)->pluck('id')->toArray();
    }
    
    public function getTotalUserComments(int $userId): int
    {
        return $this->model->whereUserId($userId)->count();
    }
    
    public function getTotalAnswersOnUserContent(int $userId): int
    {
        $postsIds = app()->make(PostRepository::class)->getUserPostsIds($userId);
        $postsAnswers = $this->model->whereCommentableType(Post::class)
            ->whereIn('commentable_id', $postsIds)
            ->count();
        
        $commentsIds = $this->getUserCommentsIds($userId);
        $commentsAnswers = $this->model->whereCommentableType(Comment::class)
            ->whereIn('commentable_id', $commentsIds)
            ->count();
        
        
        return $postsAnswers + $commentsAnswers;
    }
}