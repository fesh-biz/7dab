<?php

declare(strict_types=1);

namespace App\Redis\Services;

use App\Redis\Repositories\UserRedisRepository;

class UserRedisService
{
    public function __construct(
        public UserRedisRepository $userRedisRepo
    )
    {
    }
}
