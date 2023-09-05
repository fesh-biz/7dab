<?php

namespace App\Services\Media;

use App\Models\Media\Media;
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
}
