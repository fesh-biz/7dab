<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\TagRequest;
use App\Services\Content\TagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    protected TagService $service;
    
    public function __construct(TagService $service)
    {
        $this->service = $service;
    }
    
    public function search(Request $r): JsonResponse
    {
        $data = $this->service->search($r->title, $r->tids, $r->limit);
        
        return response()->json($data);
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
