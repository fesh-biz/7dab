<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\PostRequest;
use App\Models\Content\Post;
use App\Repositories\Content\CommentRepository;
use App\Repositories\Content\PostRepository;
use App\Services\Content\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected PostRepository $repo;
    protected PostService $service;
    
    public function __construct(PostRepository $repo, PostService $service)
    {
        $this->repo = $repo;
        $this->service = $service;
    }
    
    public function top(): JsonResponse
    {
        $posts = $this->service->getTopPosts();
        
        return $this->sendPaginationResponse($posts);
    }
    
    public function postView(int $postId): JsonResponse
    {
        $post = $this->service->findPostView($postId);
        
        return response()->json([
            'data' => $post,
            'status' => 'success'
        ]);
    }
    
    public function postPreview(int $postId): JsonResponse
    {
        $post = $this->repo->findPostPreview($postId);
        
        return response()->json([
            'data' => $post,
            'status' => 'success'
        ]);
    }
    
    public function incrementPostViewsCounter(int $id)
    {
        $this->repo->incrementPostViewsCounter($id);
    }
    
    public function store(PostRequest $r): JsonResponse
    {
        $userId = auth()->id();
        
        $errorMessage = null;
        
        $maxPostsPerDay = 5;
        $totalPostToday = $this->repo->getTotalUserPostsForToday($userId);
        if ($totalPostToday >= $maxPostsPerDay) {
            $errorMessage = trans('errors.max_allowed_post_for_today_exceeded') . " (${maxPostsPerDay})";
        }
        
        $maxDraftsPerUser = 10;
        $totalDrafts = $this->repo->getTotalUserDrafts($userId);
        if ($totalDrafts >= $maxDraftsPerUser) {
            $errorMessage = trans('errors.max_allowed_drafts_exceeded') . " (${maxDraftsPerUser})";
        }
        
        if ($errorMessage) {
            abort(422, $errorMessage);
        }
        
        
        $post = $this->service->create($r);
        
        $post = $this->repo->findPostPreview($post->id);
        
        return response()->json($post);
    }
    
    public function update(PostRequest $r, int $id): JsonResponse
    {
        $this->authorize('update', $this->repo->find($id));
        
        $this->service->update($r, $id);
        
        $post = $this->repo->findPostPreview($id);
        
        return response()->json($post);
    }
    
    public function publish(int $id): JsonResponse
    {
        $post = $this->repo->find($id);
        $this->authorize('publish', $post);
        
        $post->update(['status' => 'pending']);
        
        return response()->json([
            'status' => 'success'
        ]);
    }
    
    public function destroy(int $id): JsonResponse
    {
        $post = $this->repo->find($id);
        $this->authorize('delete', $post);
        
        $this->service->destroy($post);
        
        return response()->json([
            'status' => 'success'
        ]);
    }
    
    public function postComments(int $postId): JsonResponse
    {
        $commentRepo = app()->make(CommentRepository::class);
        $comments = $commentRepo->getPostComments($postId);
        
        return response()->json($comments);
    }
}
