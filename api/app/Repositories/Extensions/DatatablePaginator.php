<?php

namespace App\Repositories\Extensions;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

trait DatatablePaginator
{
    public function getPaginatedRecordsForAdmin(string $keywordSearchColumn): LengthAwarePaginator
    {
        $r = app()->make(Request::class);
    
        $orderBy = 'id';
        $descending = 'desc';
        if ($r->sortBy) {
            $orderBy = $r->sortBy;
            $descending = $r->descending === 'true' ? 'desc' : 'asc';
        }
        
        $query = $this->model
            ->orderBy($orderBy, $descending);
        
        if ($r->keyword) {
            $query = $query->where($keywordSearchColumn, 'like', "%$r->keyword%");
        }
        
        return $query->paginate(10);
    }
}