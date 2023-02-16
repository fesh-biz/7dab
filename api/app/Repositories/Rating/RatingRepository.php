<?php

namespace App\Repositories\Rating;

use App\Models\Rating\Rating;

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
}