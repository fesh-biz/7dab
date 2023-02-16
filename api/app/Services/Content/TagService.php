<?php

namespace App\Services\Content;

use App\Models\Content\Tag;
use App\Repositories\Content\TagRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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
    
    public function search(string $title): Collection
    {
        return $this->repo->getModel()
            ->where('title', 'like', "%$title%")
            ->limit(5)
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
