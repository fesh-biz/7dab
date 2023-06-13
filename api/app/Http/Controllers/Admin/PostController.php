<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content\Post;
use App\Repositories\Content\PostRepository;
use App\Services\Content\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected PostService $service;
    protected PostRepository $repo;
    
    public function __construct(PostService $service, PostRepository $repo)
    {
        $this->service = $service;
        $this->repo = $repo;
    }
    
    public function index(Request $r): JsonResponse
    {
        $search = [
            'keyword' => $r->keyword
        ];
        
        if ($r->sortBy) {
            $search = array_merge($search, [
                'order_by' => $r->sortBy,
                'is_descending' => $r->descending === 'true'
            ]);
        }
        
        return response()->json($this->repo->getAdminPaginatedPostsBySearch($search));
    }
    
    public function update(Request $r, $id): JsonResponse
    {
        $data = [];
        if ($r->status) {
            $data['status'] = $r->status;
        }
        
        if (count($data) > 0) {
            $post = Post::findOrFail($id);
            $post->update($data);
        }
        
        return response()->json(['status' => 'success']);
    }
    
    public function destroy(int $id): JsonResponse
    {
        $post = $this->repo->find($id);
        
        $this->service->destroy($post);
        
        return response()->json([
            'status' => 'success'
        ]);
    }
}
