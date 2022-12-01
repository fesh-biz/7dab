<?php

namespace App\Services\Content;

use App\Http\Requests\Content\PostRequest;
use App\Models\Content\Post;
use App\Repository\Content\PostRepository;
use DB;

class PostService
{
    protected PostRepository $repo;
    protected PostTextService $postTextService;
    protected PostImageService $postImageService;

    public function __construct(
        PostRepository $repo,
        PostTextService $postTextService,
        PostImageService $postImageService
    )
    {
        $this->repo = $repo;
        $this->postTextService = $postTextService;
        $this->postImageService = $postImageService;
    }

    public function create(PostRequest $data): Post
    {
        DB::beginTransaction();
        $post = $this->repo->create($data['title']);

        $sections = $data['sections'];

        foreach ($sections as $section) {
            $this->createSection($post->id, $section);
        }


        DB::commit();

        return $post;
    }

    private function createSection(int $postId, array $section): void
    {
        $order = $section['order'];
        $content = $section['content'];

        switch ($section['type']) {
            case 'text':
                $this->postTextService->create($postId, $order, $content);
                break;
            case 'image':
                $this->postImageService->create($postId, $order, $content);
                break;
            default:
                break;
        }
    }
}
