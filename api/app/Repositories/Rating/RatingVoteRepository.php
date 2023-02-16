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
}