<?php

declare(strict_types=1);

namespace App\Redis\Repositories;

use App\Data\Media\CreateMediaRedisData;
use App\Data\Media\UpdateMediaRedisData;
use App\Redis\Models\MediaRedis;

class MediaRedisRepository
{
    protected MediaRedis $model;

    public function __construct(MediaRedis $model)
    {
        $this->model = $model;
    }

    public function create(CreateMediaRedisData $data)
    {
        $this->model->create($data->id, $data->toArray());
    }

    public function update(UpdateMediaRedisData $data)
    {
        $this->model->create($data->id, $data->toArray());
    }

    public function addFileChunk(int $id, string $filename)
    {
        $redisMedia = $this->model->find($id);
        $redisMedia->chunks[] = $filename;

        $updateData = UpdateMediaRedisData::from($redisMedia);

        $this->update($updateData);
    }

    public function __call($name, $arguments)
    {
        $id = null;
        if (count($arguments) === 1) {
            $id = $arguments[0];
        }
        $res = null;
        if (method_exists($this->model, $name)) {
            $res = $this->model->{$name}($id);
        }

        return $res;
    }
}
