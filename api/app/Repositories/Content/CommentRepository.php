<?php

namespace App\Repositories\Content;

use App\Models\Content\Comment;
use App\Models\Content\Post;
use Illuminate\Pagination\LengthAwarePaginator;
use phpDocumentor\Reflection\Types\Boolean;

class CommentRepository
{
    protected Comment $model;
    
    public function __construct(Comment $model)
    {
        $this->model = $model;
    }
    
    public function getPaginatedUserCommentsWithPostsAndParents(int $userId): LengthAwarePaginator
    {
        $comments = $this->model->whereUserId($userId)->paginate(10);
        
        return $comments;
    }
    
    public function getModel(): Comment
    {
        return $this->model;
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