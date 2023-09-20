<?php

declare(strict_types=1);

namespace App\Redis\Repositories;

use App\Data\Media\CreateMediaRedisData;
use App\Data\Media\UpdateMediaRedisData;
use App\Redis\Models\MediaRedis;

class MediaRedisRepository
{
    public MediaRedis $model;

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

    public function getUploadedMediaChunks(int $mediaId):? array
    {
        $mediaRedis = $this->model->find($mediaId);

        return $mediaRedis->chunks;
    }

    public function getUploadedMediaChunksSize(int $mediaId): int
    {
        $res = 0;

        $chunks = $this->getUploadedMediaChunks($mediaId);

        foreach ($chunks as $chunk) {
            $res += $chunk->size;
        }

        return $res;
    }

    public function addFileChunk(int $id, string $filename, int $fileSize)
    {
        $redisMedia = $this->model->find($id);
        $redisMedia->chunks[] = [
            'filename' => $filename,
            'size' => $fileSize
        ];

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
