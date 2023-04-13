<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository
{
    protected User $model;
    
    public function __construct(User $model)
    {
        $this->model = $model;
    }
    
    public function getModel(): User
    {
        return $this->model;
    }
    
    public function createNewUser(array $userData): User
    {
        $userData['password'] = bcrypt($userData['password']);
        
        return $this->model->create($userData);
    }
}