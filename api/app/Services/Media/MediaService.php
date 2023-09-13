<?php

namespace App\Services\Media;

use App\Data\Media\CreateMediaData;
use App\Models\Media\Media;
use App\Redis\Models\MediaRedis;
use App\Repositories\Media\MediaRepository;

class MediaService
{
    protected MediaRepository $repo;

    public function __construct(MediaRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getModel(): Media
    {
        return $this->repo->getModel();
    }

    public function getRepo(): MediaRepository
    {
        return $this->repo;
    }

    public function create(CreateMediaData $d): int
    {
        $media = $this->repo->create($d);

        $redisId = $this->createMediaRedis($media);
    }

    private function createMediaRedis(Media $m): int
    {
        $id = $m->id;

        $mediaData = [
            'chunks' => [],
            'mime_type' => $m->mime_type
        ];

        $mediaRedis = app()->make(MediaRedis::class);
        $mediaRedis->create($id, $mediaData);

        return $id;
    }
}
