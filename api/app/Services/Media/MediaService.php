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

    public function create(CreateMediaData $d): Media
    {
        $media = $this->repo->create($d);

        $redis = app()->make(MediaRedis::class);
        $redis->create($media->id, );
    }
}
