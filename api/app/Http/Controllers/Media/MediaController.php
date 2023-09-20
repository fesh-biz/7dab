<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\CheckFileRequest;
use App\Http\Requests\Media\UploadMediaChunkRequest;
use App\Redis\Repositories\MediaRedisRepository;
use App\Services\Media\MediaFileService;
use App\Services\Media\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    protected MediaService $service;

    public function __construct(MediaService $service)
    {
        $this->service = $service;
    }

    public function checkFile(CheckFileRequest $r): JsonResponse
    {
        $mb = 1024 * 1024;
        $media = null;
        if ($r->dto()->original_size > 2 * $mb) {
            $media = $this->service->create($r->dto());
        }

        return response()->json([
            'status' => 'success',
            'chunk_size' => explode('M', ini_get('upload_max_filesize'))[0],
            'media_id' => $media->id ?? null
        ]);
    }

    public function uploadChunk(UploadMediaChunkRequest $r)
    {
        $mediaId = $r->media_id;
        $chunkIndex = $r->chunk_index;
        $totalChunks = $r->total_chunks;
        $file = $r->file('file_chunk');

        $mediaRedisRepo = app()->make(MediaRedisRepository::class);
        $uploadedMediaChunksSize = $mediaRedisRepo->getUploadedMediaChunksSize($mediaId);

        $maxChunkSize = 1024 * 1024 * getUploadMaxFilesize();
        if ($uploadedMediaChunksSize > config('7dab.media_chunks_sum_max_size') - $maxChunkSize) {
            throw new \Exception('Max sum of all chunks has been reached');
        }

        $mediaFileService = new MediaFileService();
        $mediaFileService->checkFileHasExploits($file->get());

        $filename = $mediaFileService->storeChunk($mediaId, $file);

        $mediaRedisRepo->addFileChunk($mediaId, $filename, $file->getSize());


        // if ($chunkIndex > 0) {
        //     ddh($uploadedMediaChunksSize);
        // }
    }

    public function destroy(): JsonResponse
    {
        return response()->json([
            'status' => 'success'
        ]);
    }
}
