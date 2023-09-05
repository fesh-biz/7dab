<?php

namespace App\Repositories\Media;

use App\Models\Media\Media;

class MediaRepository
{
    protected Media $model;

    public function __construct(Media $model)
    {
        $this->model = $model;
    }

    public function getModel(): Media
    {
        return $this->model;
    }
}
