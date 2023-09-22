<?php

declare(strict_types=1);

namespace App\Redis_bckp\Services;

use App\Data\Media\MediaRedisData;
use App\Data\User\UserRedisData;
use App\Redis_bckp\Repositories\MediaRedisRepository;
use App\Redis_bckp\Repositories\UserRedisRepository;

class MediaRedisService
{
    public function __construct(
        public MediaRedisRepository $mediaRedisRepo,
        public UserRedisRepository $userRedisRepo
    )
    {
    }

    public function create(MediaRedisData $data)
    {
        $userId = 15; //auth()->id();
        $userRedis = $this->userRedisRepo->find($userId);

        if (!$userRedis) {
            $userRedis = $this->userRedisRepo->create(new UserRedisData($userId));
        }
        $userRedis->media_ids[] = $data->id;
        $this->userRedisRepo->update(UserRedisData::from($userRedis));

        return $this->mediaRedisRepo->create($data);
    }

    public function addFileChunk()
    {

    }
}
