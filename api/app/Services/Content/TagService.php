<?php

namespace App\Services\Content;

use App\Models\Content\Tag;
use App\Repositories\Content\TagRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TagService
{
    protected TagRepository $repo;
    
    public function __construct(TagRepository $repo)
    {
        $this->repo = $repo;
    }
    
    public function getModel(): Tag
    {
        return $this->repo->getModel();
    }
    
    public function search(string $title = null, array $tagIds = null, int $limit = null): Collection
    {
        $query = $this->repo->getModel();
        
        
        if ($title) {
            $query = $query->where('title', 'like', "%$title%");
        }
        
        if ($tagIds) {
            $query = $query->whereIn('id', $tagIds);
        }
    
        $limit = $limit ?? 5;
        return $query->limit($limit)
            ->get();
    }
    
    public function paginatedSearch(string $keyword = null, string $orderBy = null, string $descending = null): LengthAwarePaginator
    {
        $query = $this->getModel()
            ->when($keyword, function ($q) use ($keyword) {
                $q->where('title', 'like', "%$keyword%");
            })
            ->when($orderBy, function ($q) use ($orderBy, $descending) {
                $q->orderBy($orderBy, $descending);
            });
        
        return $query->paginate(10);
    }
    
    public function createTags(array $titles): array
    {
        $ids = [];
        
        foreach ($titles as $title) {
            if ($this->repo->isExists($title)) {
                continue;
            }
            
            $ids[] = $this->repo->create($title)->id;
        }
        
        return $ids;
    }
}
