<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\PostRequest;
use App\Repository\Content\PostRepository;
use App\Services\Content\PostService;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    protected PostRepository $postRepo;
    protected PostService $postService;

    public function __construct(PostRepository $postRepo, PostService $postService)
    {
        $this->postRepo = $postRepo;
        $this->postService = $postService;
    }

    public function index(): JsonResponse
    {
        $posts = $this->postRepo->getPaginatedPosts();

        return $this->sendPaginationResponse($posts);
    }

    public function post(int $postId): JsonResponse
    {
        $post = $this->postRepo->findPost($postId);

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
        $post = $this->postService->create($r);

        return $this->response($post);
    }
}
