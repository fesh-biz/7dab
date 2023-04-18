<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\PostRequest;
use App\Models\Content\Post;
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
    
    public function profilePosts (Request $r): JsonResponse
    {
        $search = [
            'userId' => auth()->id(),
            'withoutMyVotes' => true,
            'title' => $r->keyword
        ];
        if (in_array($r->status, ['approved', 'draft', 'pending'])) {
            $search['status'] = $r->status;
        }
        
        $posts = $this->repo->getPaginatedPosts($search);

        return response()->json($posts);
    }

    public function index(): JsonResponse
    {
        $posts = $this->service->getPaginatedPosts(true);

        return $this->sendPaginationResponse($posts);
    }

    public function incrementPostViewsCounter(int $id)
    {
        $this->repo->incrementViews($id);
    }

    public function post(int $postId, Request $r): JsonResponse
    {
        $isPreview = intval($r->preview) === 1;

        if ($isPreview) {
            $post = $this->repo->findWithBasicRelationships($postId, true);
        } else {
            $post = $this->service
                ->findPostWithBasicRelationshipsWithIncrementingViews($postId);
        }

        if (!$post) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Not found'
            ], 404);
        }

        return response()->json([
            'data' => $post,
            'status' => 'success'
        ]);
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

        $post = $this->repo->findWithBasicRelationships($post->id);

        return response()->json($post);
    }

    public function update(PostRequest $r, int $id): JsonResponse
    {
        $this->authorize('update', $this->repo->find($id));
        
        $this->service->update($r, $id);

        $post = $this->repo->findWithBasicRelationships($id, true);

        return response()->json($post);
    }

    public function publish(int $id): JsonResponse
    {
        $post = $this->repo->find($id);
        $this->authorize('publish', $post);
        
        $this->service->publish($post);
        
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
}
