<?php

namespace App\Services\Content;

use App\Http\Requests\Content\PostRequest;
use App\Models\Content\Post;
use App\Models\Content\PostImage;
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
    protected TagService $tagService;
    
    public function __construct(
        PostRepository $repo,
        PostTextService $postTextService,
        PostImageService $postImageService,
        PostStatService $postStatService,
        TagService $tagService
    )
    {
        $this->repo = $repo;
        $this->postTextService = $postTextService;
        $this->postImageService = $postImageService;
        $this->postStatService = $postStatService;
        $this->tagService = $tagService;
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
        
        $tags = $data->tags;
        $newTags = array_column($tags, 'new');
        $existedTags = array_column($tags, 'id');
        $existedTags = array_merge($existedTags, $this->tagService->createTags($newTags));
        $post->tags()->sync($existedTags);
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
        
        $sections = $postImages->concat($postTexts);
        
        foreach ($sections as $i => $section) {
            $model = explode('\\', get_class($section));
            $model = end($model);
            
            $types = [
                'PostImage' => 'image',
                'PostText' => 'text'
            ];
            
            $section->setAttribute('type', $types[$model]);
        }
        
        return $sections;
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
    
    private function deleteSections(array $sectionsToDelete): void
    {
        foreach ($sectionsToDelete as $i => $section) {
            if ($section['type'] === 'text') {
                $this->postTextService->getModel()->whereId($section['id'])->delete();
            }
            
            if ($section['type'] === 'image') {
                $this->postImageService->delete($section['id']);
            }
        }
    }
    
    private function updateSections(int $postId, array $sectionsFromInput): void
    {
        $postSections = $this->getPostSections($postId);
        
        // Updating/Creating
        foreach ($sectionsFromInput as $i => $inputSection) {
            if (!($inputSection['id'] ?? false)) {
                $this->createSection($postId, $inputSection);
                continue;
            }
            
            $id = $inputSection['id'];
            
            // Text
            if ($inputSection['type'] === 'text') {
                /** @var PostText $section */
                $section = $postSections->where('type', 'text')
                    ->where('id', $id)
                    ->first();
                
                $data = [];
                if ($section->order !== (int)$inputSection['order']) {
                    $data['order'] = $inputSection['order'];
                }
                if ($section->body !== $inputSection['content']) {
                    $data['body'] = $inputSection['content'];
                }
                
                if (count($data)) {
                    $this->postTextService->update($section->id, $data);
                }
            }
            
            // Image
            if ($inputSection['type'] === 'image') {
                /** @var PostImage $section */
                $section = $postSections->where('type', 'image')
                    ->where('id', $id)
                    ->first();
                
                $data = [];
                if ($section->order !== (int)$inputSection['order']) {
                    $data['order'] = $inputSection['order'];
                }
                
                if ($section->title !== $inputSection['content']['title']) {
                    $data['title'] = $inputSection['content']['title'];
                }
                
                if (array_key_exists('file', $inputSection['content'])) {
                    $data['file'] = $inputSection['content']['file'];
                }
                
                if (count($data)) {
                    $this->postImageService->update($section->id, $data);
                }
            }
        }
        
        // Deleting
        $sectionsToDelete = [];
        foreach ($postSections as $i => $section) {
            $isSectionExistsInInput = false;
            foreach ($sectionsFromInput as $inputSection) {
                if (!array_key_exists('id', $inputSection)) {
                    continue;
                }
                
                if ((int)$section->id === (int)$inputSection['id'] && $section->type === $inputSection['type']) {
                    $isSectionExistsInInput = true;
                    break;
                }
            }
            
            if (!$isSectionExistsInInput) {
                $sectionsToDelete[] = [
                    'id' => $section->id,
                    'type' => $section->type
                ];
            }
        }
        
        $this->deleteSections($sectionsToDelete);
    }
}
