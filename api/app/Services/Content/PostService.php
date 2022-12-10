<?php

namespace App\Services\Content;

use App\Http\Requests\Content\PostRequest;
use App\Models\Content\Post;
use App\Models\Content\PostText;
use App\Repository\Content\PostRepository;
use DB;
use Illuminate\Database\Eloquent\Collection;

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
        $post = $this->repo->find($postId);

        DB::beginTransaction();
        $post->title = $data['title'];

        $this->updateSections($postId, $data['sections']);

        $post->save();
        DB::commit();

        return $post;
    }

    public function getPostSections(int $postId): Collection
    {
        $postImages = $this->postImageService->getModel()
            ->wherePostId($postId)
            ->get();
        $postTexts = $this->postTextService->getModel()
            ->wherePostId($postId)
            ->get();

        return $postImages->concat($postTexts);
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

    private function updateOrCreateSection(int $postId, array $section): void
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

    private function updateSections(int $postId, array $sectionsFromInput): void
    {
        $postSections = $this->getPostSections($postId);

        foreach ($sectionsFromInput as $i => $inputSection) {
            if (!($inputSection['id'] ?? false)) {
                abort(422, 'Section ID missing');
            }

            $id = $inputSection['id'];
            if ($inputSection['type'] === 'text') {
                /** @var PostText $section */
                $section = $postSections->find($id);
                if ($section->body !== $inputSection['content']) {
                    $section->update([
                        'order' => $inputSection['order'],
                        'body' => $inputSection['content']
                    ]);
                }
            }
        }
    }
}
