<?php

declare(strict_types=1);

namespace App\Redis\Services;

use App\Data\Media\CreateMediaRedisData;
use App\Data\User\UserRedisData;
use App\Plugins\Redis\RedisException;
use App\Redis\Models\MediaRedis;
use App\Redis\Models\UserRedis;
use App\Redis\Repositories\MediaRedisRepository;
use App\Redis\Repositories\UserRedisRepository;

class MediaRedisService
{
    public function __construct(
        public MediaRedisRepository $mediaRedisRepo,
        public UserRedisRepository $userRedisRepo
    )
    {
    }

    public function create(CreateMediaRedisData $data): MediaRedis
    {
        $userId = auth()->id();
        if (!$userId) {
            throw new RedisException('User is unauthenticated');
        }

        /** @var UserRedis $userRedis */
        $userRedis = $this->userRedisRepo->find($userId);

        if (!$userRedis) {
            $userRedis = $this->userRedisRepo->create(new UserRedisData($userId));
        }
        $this->userRedisRepo->addMediaId($userRedis->id, $data->id);

        return $this->mediaRedisRepo->create($data);
    }

    public function delete(int $mediaId)
    {
        /** @var MediaRedis $mediaRedis */
        $mediaRedis = $this->mediaRedisRepo->find($mediaId);

        $userId = $mediaRedis->auth_user_id;
        $this->userRedisRepo->deleteMediaId($userId, $mediaId);
        $this->userRedisRepo->deleteUserIfEmptyMediaIds($userId);

        $mediaRedis->delete();
    }
}
