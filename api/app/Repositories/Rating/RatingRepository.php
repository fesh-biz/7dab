<?php

namespace App\Repositories\Rating;

use App\Models\Rating\Rating;
use App\Models\User;

class RatingRepository
{
    protected Rating $model;
    
    public function __construct(Rating $model)
    {
        $this->model = $model;
    }
    
    public function getModel(): Rating
    {
        return $this->model;
    }
    
    public function getUserRating(int $id):? array
    {
        $res = $this->model->whereRatingableType(User::class)
            ->whereRatingableId($id)
            ->first();
        
        if (!$res) return null;
        
        return [
            'positive' => $res->positive_votes,
            'negative' => $res->negative_votes
        ];
    }
}