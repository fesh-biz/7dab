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
    protected PostStatService $postStatService;

    public function __construct(
        PostRepository $repo,
        PostTextService $postTextService,
        PostImageService $postImageService,
        PostStatService $postStatService
    )
    {
        $this->repo = $repo;
        $this->postTextService = $postTextService;
        $this->postImageService = $postImageService;
        $this->postStatService = $postStatService;
    }

    public function create(PostRequest $data): Post
    {
        DB::beginTransaction();
        $post = $this->repo->create($data['title']);

        $sections = $data['sections'];

        foreach ($sections as $section) {
            $this->createSection($post->id, $section);
        }

        $this->postStatService->create($post->id);
        DB::commit();

        return $post;
    }

    public function update(PostRequest $data, int $postId): Post
    {
        $post = $this->repo->findPost($postId);

        DB::beginTransaction();
        $post->title = $data['title'];

        $sections = $data['sections'];

        foreach ($sections as $section) {
            $this->updateSection($post->id, $section);
        }

        $post->save();
        DB::commit();

        return $post;
    }

    public function getPostSections() {

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

    private function updateSection(int $postId, array $section): void
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
