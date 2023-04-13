<?php

namespace App\Services\User;

use App\Models\User;
use App\Repositories\Rating\RatingRepository;
use App\Repositories\User\UserRepository;

class UserService
{
    protected UserRepository $repo;
    
    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }
    
    public function getModel(): User
    {
        return $this->repo->getModel();
    }
    
    public function getStats(int $userId): array
    {
        $ratingRepo = app()->make(RatingRepository::class);
        $res['rating'] = $ratingRepo->getUserRating($userId);
        
        return $res;
    }
}