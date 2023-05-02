<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\TagRequest;
use App\Repositories\Content\TagRepository;
use App\Services\Content\TagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    protected TagService $service;
    protected TagRepository $repo;
    
    public function __construct(TagService $service, TagRepository $repo)
    {
        $this->service = $service;
        $this->repo = $repo;
    }
    
    public function paginatedSearch(Request $r): JsonResponse
    {
        $desc = $r->descending === 'true' ? 'desc' : 'asc';
        $orderBy = $r->sortBy;
        $keyword = $r->keyword;
        
        return response()->json(
            $this->service->paginatedSearch($keyword, $orderBy, $desc)
        );
    }
    
    public function update(TagRequest $r, $id): JsonResponse
    {
        $tag = $this->service->getModel()->findOrFail($id);
        $tag->update($r->validated());
        
        return response()->json(['status' => 'success']);
    }
}
