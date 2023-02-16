<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    protected User $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function createNewUser(array $userData): User
    {
        $userData['password'] = bcrypt($userData['password']);

        return $this->model->create($userData);
    }
}
