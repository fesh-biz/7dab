<?php

namespace App\Repositories\Extensions;

use Illuminate\Pagination\LengthAwarePaginator;

trait SortedPaginator
{
    public function getPaginatedRecordsForAdmin(
        string $keyword = null,
        string $keyColumn = null,
        string $descending = 'desc',
        string $orderColumn = 'id'
    ): LengthAwarePaginator
    {
        $query = $this->model
            ->orderBy($orderColumn, $descending);
        
        if ($keyword) {
            $query = $query->where($keyColumn, 'like', "%$keyword%");
        }
        
        return $query->paginate(10);
    }
}