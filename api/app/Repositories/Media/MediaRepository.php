<?php

namespace App\Repositories\Media;

use App\Data\Media\CreateMediaData;
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

    public function create(CreateMediaData $data): Media
    {
        return $this->model->create($data->toArray());
    }
}
