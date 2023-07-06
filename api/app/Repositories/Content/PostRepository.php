<?php

namespace App\Repositories\Content;

use App\Models\Content\Post;
use App\Repositories\Extensions\DatatablePaginator;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use phpDocumentor\Reflection\Types\Boolean;

class PostRepository
{
    use DatatablePaginator;
    
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
    
    public function incrementPostsViewsCounters(array $ids)
    {
        $this->model->whereIn('id', $ids)
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
        
        $authId = auth()->id();
        if ($authId !== 1 && $authId !== $post->user_id) {
            abort(401, 'Unauthorized');
        }
        
        $post->setRelation('tags', $post->previewTags);
        $post->makeHidden('previewTags');
        
        return $post;
    }
    
    public function getPaginatedPostsBySearch(array $search = null): LengthAwarePaginator
    {
        $tagsIds = $search['tags_ids'] ?? null;
        $keyword = $search['keyword'] ?? null;
        $userId = $search['user_id'] ?? null;
    
        $query = $this->model
            ->whereStatus('approved')
            ->with([
                'tags:id,title',
                'user:id,login,avatar',
                'postImages',
                'rating',
                'postTexts',
                'postYouTubes'
            ])
            ->orderBy('id', 'desc');
        
        if ($userId) {
            $query = $query->whereUserId($userId);
        }
    
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
    
        return $query->paginate(10);
    }
    
    public function getProfilePaginatedPosts(string $status, string $keyword = null): LengthAwarePaginator
    {
        $userId = auth()->id();
        
        $q = $this->model->whereUserId($userId)
            ->whereStatus($status)
            ->with('rating')
            ->orderBy('id', 'desc');
        
        if ($keyword) {
            $q = $q->where('title', 'like', "%$keyword%");
        }
        
        return $q->paginate(10);
    }
    
    public function create(array $data): Post
    {
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
    
    public function incrementCommentsCounter(int $id): int
    {
        return $this->model->whereId($id)->increment('comments');
    }
    
    public function getUserPostsIds(int $userId): array
    {
        return $this->model->whereUserId($userId)->pluck('id')->toArray();
    }
    
    public function find(int $postId): ?Post
    {
        return $this->model->findOrFail($postId);
    }
}
