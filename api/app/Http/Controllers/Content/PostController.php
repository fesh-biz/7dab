<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\Post;
use App\Repository\Content\PostRepository;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    protected PostRepository $postRepo;

    public function __construct(PostRepository $postRepo)
    {
        $this->postRepo = $postRepo;
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
}
