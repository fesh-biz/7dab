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

    public function index(): JsonResponse
    {
        $posts = $this->service->getPaginatedPostsWithIncrementingOfViews();

        return $this->sendPaginationResponse($posts);
    }

    public function incrementViews(int $id)
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
        $post = $this->service->create($r);

        $post = $this->repo->findWithBasicRelationships($post->id);

        return $this->response($post);
    }

    public function update(PostRequest $r, int $id): JsonResponse
    {
        $this->service->update($r, $id);

        $post = $this->repo->findWithBasicRelationships($id, true);

        return $this->response($post);
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
        $this->service->destroy($id);
    }
}
