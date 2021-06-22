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
}
