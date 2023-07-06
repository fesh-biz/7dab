<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\Extensions\SortedPaginator;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    use SortedPaginator;
    
    protected User $model;
    
    public function __construct(User $model)
    {
        $this->model = $model;
    }
    
    public function getModel(): User
    {
        return $this->model;
    }
    
    // Temporary Fake user
    public function getRandomFakeUser(): User
    {
        return $this->model->where('email', 'like', '%@terevenky.com%')->inRandomOrder()->first();
    }
    
    public function createNewUser(array $userData): User
    {
        $userData['password'] = bcrypt($userData['password']);
        
        return $this->model->create($userData);
    }
}