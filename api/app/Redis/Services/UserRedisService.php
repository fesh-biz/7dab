<?php

declare(strict_types=1);

namespace App\Redis\Services;

use App\Redis\Models\UserRedis;
use App\Redis\Repositories\UserRedisRepository;

class UserRedisService
{
    public function __construct(
        public UserRedisRepository $userRedisRepo
    )
    {
    }

    public function deleteUserIfEmptyMediaIds(int $userId)
    {
        /** @var UserRedis $user */
        $user = $this->userRedisRepo->find($userId);

        if (!count($user->media_ids)) {
            $user->delete();
        }
    }
}
