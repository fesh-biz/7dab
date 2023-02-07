<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Services\Content\TagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    protected TagService $service;
    
    public function __construct(TagService $service)
    {
        $this->service = $service;
    }
    
    public function search(Request $r): JsonResponse
    {
        $data = $this->service->search($r->title);
        
        return $this->response($data);
    }
    
    public function paginatedSearch(Request $r): JsonResponse
    {
        return response()->json($this->service->paginatedSearch($r->keyword, $r->order_by, $r->descending));
    }
}
