<?php

declare(strict_types=1);

namespace App\Redis\Services;

use App\Data\Media\MediaRedisData;
use App\Data\User\UserRedisData;
use App\Redis\Models\MediaRedis;
use App\Redis\Models\UserRedis;
use App\Redis\Repositories\MediaRedisRepository;
use App\Redis\Repositories\UserRedisRepository;
use App\Services\Media\MediaFileService;

class MediaRedisService
{
    public function __construct(
        public MediaRedisRepository $mediaRedisRepo,
        public UserRedisRepository $userRedisRepo
    )
    {
    }

    public function create(MediaRedisData $data): MediaRedis
    {
        $userId = auth()->id();

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
        $fileService = app()->make(MediaFileService::class);
        $fileService->deleteMediaChunksDirectory($mediaId);

        $this->mediaRedisRepo->delete($mediaId);

        $userRedisService = app()->make(UserRedisService::class);
        $userRedisService->deleteUserIfEmptyMediaIds($mediaId);
    }
}
