<?php

namespace App\Services\Content;

use App\Http\Requests\Content\PostRequest;
use App\Models\Content\Post;
use App\Models\Content\PostImage;
use App\Models\Content\PostText;
use App\Models\Content\PostYouTube;
use App\Repositories\Content\PostRepository;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PostService
{
    protected PostRepository $repo;
    protected PostTextService $postTextService;
    protected PostImageService $postImageService;
    protected TagService $tagService;
    protected PostYouTubeService $postYouTubeService;
    
    public function __construct(
        PostRepository $repo,
        PostTextService $postTextService,
        PostImageService $postImageService,
        TagService $tagService,
        PostYouTubeService $postYouTubeService
    )
    {
        $this->repo = $repo;
        $this->postTextService = $postTextService;
        $this->postImageService = $postImageService;
        $this->tagService = $tagService;
        $this->postYouTubeService = $postYouTubeService;
    }
    
    public function getPaginatedPosts(bool $incrementViewsCounters, array $searchCondition = []): LengthAwarePaginator
    {
        $posts = $this->repo->getPaginatedPosts($searchCondition);
        
        if ($incrementViewsCounters) {
            $this->repo->incrementViewsMultiple($posts->pluck('id')->toArray());
        }
        
        return $posts;
    }
    
    public function findPostWithBasicRelationshipsWithIncrementingViews(int $id): Post
    {
        $post = $this->repo->findWithBasicRelationships($id);
        
        $this->repo->incrementViews($post->id);
        
        return $post;
    }
    
    public function create(PostRequest $data): Post
    {
        DB::beginTransaction();
        $post = $this->repo->create($data['title']);
        
        $sections = $data['sections'];
        
        foreach ($sections as $section) {
            $this->createSection($post->id, $section);
        }
        
        $tags = $data->tags;
        $this->syncWithTags($tags, $post);
        DB::commit();
        
        return $post;
    }
    
    public function update(PostRequest $data, int $postId): Post
    {
        $post = $this->repo->find($postId);
        
        $totalImages = 0;
        if (count($data->allFiles())) {
            $totalImages = count($data->allFiles()['sections']);
            $totalImages += $post->postImages()->count();
        }
        
        $maxAllowedFiles = intval(ini_get('max_file_uploads')) - 1;
        if ($totalImages > $maxAllowedFiles) {
            abort(422, trans('errors.max_allowed_files_exceeded') . " ($maxAllowedFiles)");
        }
        
        DB::beginTransaction();
        $post->title = $data['title'];
        
        $this->updateSections($postId, $data['sections']);
        
        $post->save();
        
        $tags = $data->tags;
        $this->syncWithTags($tags, $post);
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
        $postYouTubes = $this->postYouTubeService->getModel()
            ->wherePostId($postId)
            ->get();
        
        $sections = $postImages->concat($postTexts)->concat($postYouTubes);
        
        foreach ($sections as $i => $section) {
            $model = explode('\\', get_class($section));
            $model = end($model);
            
            $types = [
                'PostImage' => 'image',
                'PostText' => 'text',
                'PostYouTube' => 'youtube'
            ];
            
            if (!$types[$model]) {
                throw new \ErrorException('Type for given model ' . $model . ' not found');
            }
            
            $section->setAttribute('type', $types[$model]);
        }
        
        return $sections;
    }
    
    public function publish(Post $post)
    {
        $post->update(['status' => 'pending']);
    }
    
    public function destroy(Post $post)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $post->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
    
    private function syncWithTags(array $tagsFromInput, Post $post)
    {
        $newTags = array_column($tagsFromInput, 'new');
        $existedTags = array_column($tagsFromInput, 'id');
        $existedTags = array_merge($existedTags, $this->tagService->createTags($newTags));
        $post->tags()->sync($existedTags);
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
            case 'youtube':
                $this->postYouTubeService->repo->create($postId, [
                    'order' => $order,
                    'youtube_id' => $content['youtube_id']
                ]);
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
            
            if ($section['type'] === 'youtube') {
                $this->postYouTubeService->getModel()->whereId($section['id'])->delete();
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
            
            // Youtube
            if ($inputSection['type'] === 'youtube') {
                /** @var PostYouTube $section */
                $section = $postSections->where('type', 'youtube')
                    ->where('id', $id)
                    ->first();
                
                $data = [];
                if ($section->order !== (int)$inputSection['order']) {
                    $data['order'] = $inputSection['order'];
                }
                
                if ($section->youtube_id !== $inputSection['content']['youtube_id']) {
                    $data['youtube_id'] = $inputSection['content']['youtube_id'];
                }
                
                if (count($data)) {
                    $this->postYouTubeService->repo->update($section->id, $data);
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
