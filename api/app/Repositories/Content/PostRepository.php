<?php

namespace App\Repositories\Content;

use App\Models\Content\Post;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use phpDocumentor\Reflection\Types\Boolean;

class PostRepository
{
    protected Post $model;
    
    public function __construct(Post $model)
    {
        $this->model = $model;
    }
    
    public function getTopPosts(): LengthAwarePaginator
    {
        $q = $this->model
            ->with([
                'tags:id,title',
                'user:id,login,avatar',
                'postImages',
                'rating',
                'postTexts',
                'postYouTubes'
            ]);
        
        if (auth('api')->user()) {
            $q->with('myVote');
        }
        
        return $q->whereStatus('approved')
            ->orderBy('id', 'desc')
            ->paginate(10);
    }
    
    public function findPostView(int $id): Post
    {
        $q = $this->model
            ->whereStatus('approved')
            ->with([
                'tags:id,title',
                'user:id,login,avatar',
                'postImages',
                'rating',
                'postTexts',
                'postYouTubes'
            ]);
        
        if (auth('api')->user()) {
            $q = $q->with('myVote');
        }
        
        return $q->findOrFail($id);
    }
    
    public function incrementPostViewsCounter(int $id)
    {
        $this->model->where('id', $id)
            ->increment('views');
    }
    
    public function findPostPreview(int $id): Post
    {
        $post = $this->model
            ->with([
                'previewTags:id,title',
                'user:id,login,avatar',
                'postImages',
                'postTexts',
                'postYouTubes'
            ])->findOrFail($id);
        
        if (auth()->id() !== $post->user_id) {
            abort(401, 'Unauthorized');
        }
        
        $post->setRelation('tags', $post->previewTags);
        $post->makeHidden('previewTags');
        
        return $post;
    }
    
    // Non Refactored
    
    public function incrementComments(int $id): int
    {
        return $this->model->whereId($id)->increment('comments');
    }
    
    // public function getPaginatedPosts(array $search = null): LengthAwarePaginator
    // {
    //     $status = $search['status'] ?? 'approved';
    //     $tagsIds = $search['tagsIds'] ?? null;
    //     $title = $search['title'] ?? null;
    //     $userId = $search['userId'] ?? null;
    //
    //     $query = $this->model
    //         ->whereStatus($status)
    //         ->orderBy('id', 'desc');
    //
    //     if ($userId) {
    //         $query = $query->whereUserId($userId);
    //     }
    //
    //     if ($tagsIds) {
    //         $query = $query->whereHas('tags', function ($q) use ($tagsIds) {
    //             $q->whereIn('id', $tagsIds);
    //         }, '=', count($tagsIds));
    //     }
    //
    //     if ($title) {
    //         $query = $query->where('title', 'like', "%$title%");
    //     }
    //
    //     if (auth('api')->user()) {
    //         $query->with('myVote');
    //     }
    //
    //     return $query->withTagsAuthorContent()
    //         ->paginate(10);
    // }
    
    public function getUserPostsIds(int $userId): array
    {
        return $this->model->whereUserId($userId)->pluck('id')->toArray();
    }
    
    public function incrementViewsMultiple(array $ids)
    {
        $this->model->whereIn('id', $ids)
            ->increment('views');
    }
    
    // public function incrementViews(int $id)
    // {
    //     $this->model->where('id', $id)
    //         ->increment('views');
    // }
    
    public function find(int $postId): ?Post
    {
        return $this->model->findOrFail($postId);
    }
    
    // public function findWithBasicRelationships(int $postId, bool $isPreview = false): ?Post
    // {
    //     $res = $this->model;
    //
    //     if (!$isPreview) {
    //         $res = $res->withTagsAuthorContent()->findOrFail($postId);
    //     } else {
    //         $res = $res->withPreviewRelations()->findOrFail($postId);
    //
    //         $res->setRelation('tags', $res->previewTags);
    //         $res->makeHidden('previewTags');
    //     }
    //
    //     return $res;
    // }
    
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
    
    public function getTotalUserPostsForToday(int $userId): int
    {
        return $this->model
            ->whereUserId($userId)
            ->whereDate('created_at', Carbon::today())->count();
    }
    
    public function getTotalUserDrafts(int $userId): int
    {
        return $this->getTotalUserPosts($userId, 'draft');
    }
    
    public function getTotalUserApprovedPosts(int $userId): int
    {
        return $this->getTotalUserPosts($userId, 'approved');
    }
    
    public function getTotalUserPosts(int $userId, string $status = null): int
    {
        $q = $this->model
            ->whereUserId($userId);
        
        if ($status) {
            $q->whereStatus($status);
        }
        
        return $q->count();
    }
    
    public function getTotalUserPostsByStatuses(int $userId, array $statuses): array
    {
        $postsCountsByStatus = \DB::table('posts')
            ->where('user_id', $userId)
            ->select('status', \DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()->toArray();
        
        $res = ['total' => 0];
        foreach ($postsCountsByStatus as $count) {
            if (in_array($count->status, $statuses)) {
                $res[$count->status] = $count->count;
                $res['total'] += $count->count;
            }
        }
        
        return $res;
    }
}
