<?php

namespace App\Services\Content;

use App\Repository\Content\TagRepository;
use Illuminate\Database\Eloquent\Collection;

class TagService
{
    protected TagRepository $repo;
    
    public function __construct(TagRepository $repo)
    {
        $this->repo = $repo;
    }
    
    public function getModel(): TagRepository
    {
        return $this->repo;
    }
    
    public function search(string $title): Collection
    {
        return $this->repo->getModel()
            ->where('title', 'like', "%$title%")
            ->limit(5)
            ->get();
    }
}
