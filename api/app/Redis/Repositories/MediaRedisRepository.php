<?php

namespace App\Redis\Repositories;

use App\Data\Media\MediaRedisData;
use App\Redis\Models\MediaRedis;

class MediaRedisRepository extends Repository
{
    public MediaRedis $model;

    public function __construct(MediaRedis $model)
    {
        $this->model = $model;
    }

    public function create(MediaRedisData $data): MediaRedis
    {
        return $this->model->create($data->toArray());
    }

    public function update(int $id, MediaRedisData $data)
    {
        return $this->model->update($id, $data->toArray());
    }

    public function incrementFailedAttempts(int $mediaId): MediaRedis
    {
        $media = $this->model->find($mediaId);

        $media->failed_attempts++;
        $media->save();

        return $media;
    }

    public function getUploadedMediaChunks(int $mediaId):? array
    {
        $media = $this->model->find($mediaId);

        return $media->chunks;
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

    public function addFileChunk(int $id, string $filename, int $fileSize): MediaRedis
    {
        /** @var MediaRedis $media */
        $media = $this->model->find($id);
        $chunks = $media->chunks;
        $chunks[] = [
            'filename' => $filename,
            'size' => $fileSize
        ];
        $media->chunks = $chunks;

        $media->save();

        return $media;
    }
}
