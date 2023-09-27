<?php

namespace App\Services\Media;

use App\Data\Media\CreateMediaData;
use App\Data\Media\MediaRedisData;
use App\Data\Media\UploadMediaChunkData;
use App\Models\Media\Media;
use App\Redis\Repositories\MediaRedisRepository;
use App\Redis\Services\MediaRedisService;
use App\Redis\Services\UserRedisService;
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
        $mediaRedisService->create(MediaRedisData::from($media));

        return $media;
    }

    public function delete(int $id)
    {
        $this->getModel()->destroy($id);;
    }

    public function uploadChunk(UploadMediaChunkData $data):? string
    {
        $mediaId = $data->media_id;
        $mediaRedisRepo = app()->make(MediaRedisRepository::class);
        $uploadedMediaChunksSize = $mediaRedisRepo->getUploadedMediaChunksSize($mediaId);

        $maxChunkSize = 1024 * 1024 * getUploadMaxFilesize();
        if ($uploadedMediaChunksSize > config('7dab.media_chunks_sum_max_size') - $maxChunkSize) {
        // if ($data->chunk_index > 4) {
            throw new MediaException('Max sum of all chunks has been reached');
        }


        $file = $data->file_chunk;
        $mediaFileService = new MediaFileService();
        $mediaFileService->checkFileHasExploits($file->get());

        $filename = $mediaFileService->storeChunk($mediaId, $file);

        $mediaRedisRepo->addFileChunk($mediaId, $filename, $file->getSize());

        $chunkIndex = $data->chunk_index;
        $totalChunks = $data->total_chunks;

        $mediaRedis = $mediaRedisRepo->find($mediaId);
        if ($chunkIndex + 1 === $totalChunks) {
            $filename = $mediaFileService->mergeFileChunks($mediaId, $mediaRedis->mime_type, $mediaRedis->chunks);

            $this->deleteMediaWithRedis($mediaId);

            return $filename;
        }

        return null;
    }

    public function deleteMediaWithRedis(int $mediaId)
    {
        $media = $this->getModel()->find($mediaId);

        $mediaRedisService = app()->make(MediaRedisService::class);
        $mediaRedisService->mediaRedisRepo->delete($media->id);

        $userRedisService = app()->make(UserRedisService::class);
        $userRedisService->deleteUserIfEmptyMediaIds($media->user_id);

        $fileService = app()->make(MediaFileService::class);
        $fileService->deleteMediaChunksDirectory($media->id);

        $media->delete();
    }
}
