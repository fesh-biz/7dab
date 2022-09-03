<?php

namespace App\Services\Content;

use App\Http\Requests\Content\PostRequest;
use App\Models\Content\Post;
use App\Repository\Content\PostRepository;
use App\Repository\Content\PostTextRepository;
use Illuminate\Database\Eloquent\Model;

class PostService
{
    protected PostRepository $repo;
    protected PostTextRepository $postTextRepo;

    public function __construct(PostRepository $repo, PostTextRepository $postTextRepo)
    {
        $this->repo = $repo;
        $this->postTextRepo = $postTextRepo;
    }

    public function create(PostRequest $data): Post
    {
        $post = $this->repo->create($data['title']);

        $sections = $data['data'];

        foreach ($sections as $section) {
            $this->createSection($post->id, $section);
        }

        return $post;
    }

    private function createSection(int $postId, array $section): void
    {
        switch ($section['type']) {
            case 'text':
                $this->postTextRepo->create($postId, $section['order'], $section['content']);
                break;
            default:
                break;
        }
    }
}
