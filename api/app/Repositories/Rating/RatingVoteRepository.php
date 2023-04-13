<?php

namespace App\Repositories\Rating;

use App\Models\Rating\RatingVote;

class RatingVoteRepository
{
    protected RatingVote $model;
    
    public function __construct(RatingVote $model)
    {
        $this->model = $model;
    }
    
    public function getModel(): RatingVote
    {
        return $this->model;
    }
    
    public function getUserVotes(int $id): array
    {
        $positive = $this->model->whereUserId($id)
            ->whereIsPositive(true)
            ->count();

        $negative = $this->model->whereUserId($id)
            ->whereIsPositive(false)
            ->count();
        
        return compact('positive', 'negative');
    }
}