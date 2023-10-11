<?php

namespace App\Services\Media;

use App\Data\Media\CreateMediaData;
use App\Data\Media\CreateMediaRedisData;
use App\Data\Media\MediaRedisData;
use App\Data\Media\UploadMediaChunkData;
use App\Models\Media\Media;
use App\Plugins\Redis\RedisException;
use App\Redis\Repositories\MediaRedisRepository;
use App\Redis\Services\MediaRedisService;
use App\Repositories\Media\MediaRepository;

class MediaService
{
    public MediaRepository $repo;

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

        $mediaRedisService = app()->make(MediaRedisService::class);

        $data = new CreateMediaRedisData(
            $media->id,
            $d->mime_type,
            $this->getTotalChunks($d->original_size)
        );
        $mediaRedisService->create($data);

        return $media;
    }

    public function storeChunk(UploadMediaChunkData $data): string
    {
        $mediaId = $data->media_id;
        $mediaRedisRepo = app()->make(MediaRedisRepository::class);
        $uploadedMediaChunksSize = $mediaRedisRepo->getUploadedMediaChunksSize($mediaId);

        if ($uploadedMediaChunksSize + $data->file_chunk->getSize() > config('7dab.media_chunks_sum_max_size')) {
            throw new MediaException('Max sum of all chunks has been reached');
        }

        $file = $data->file_chunk;
        $mediaFileService = new MediaFileService();
        $mediaFileService->checkFileHasExploits($file->get());

        $filename = $mediaFileService->storeChunk($mediaId, $file);

        $mediaRedisRepo->addFileChunk($mediaId, $filename, $file->getSize());

        $chunkIndex = $data->chunk_index;
        $totalChunks = $mediaRedisRepo->find($mediaId)->total_chunks;

        $mediaRedis = $mediaRedisRepo->find($mediaId);
        if ($chunkIndex + 1 === $totalChunks) {
            $filename = $mediaFileService->mergeFileChunks($mediaId, $mediaRedis->mime_type, $mediaRedis->chunks);

            $mediaRedisService = app()->make(MediaRedisService::class);
            $mediaRedisService->delete($mediaId);
        }

        return $filename;
    }

    public function getTotalChunks($fileSize): int
    {
        $mb = 1024 * 1024;
        $uploadSize = $mb * getUploadMaxFilesize();
        return (int) ceil($fileSize / $uploadSize);
    }
}
