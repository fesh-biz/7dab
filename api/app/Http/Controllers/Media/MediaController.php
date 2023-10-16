<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\CheckFileRequest;
use App\Http\Requests\Media\UploadMediaChunkRequest;
use App\Redis\Repositories\MediaRedisRepository;
use App\Redis\Repositories\UserRedisRepository;
use App\Redis\Services\MediaRedisService;
use App\Redis\Services\UserRedisService;
use App\Services\Media\MediaException;
use App\Services\Media\MediaFileService;
use App\Services\Media\MediaService;
use Illuminate\Http\JsonResponse;

class MediaController extends Controller
{
    protected MediaService $service;

    public function __construct(MediaService $service)
    {
        $this->service = $service;
    }

    public function checkFile(CheckFileRequest $r): JsonResponse
    {
        $media = $this->service->create($r->dto());

        return response()->json([
            'status' => 'success',
            'chunk_size' => explode('M', ini_get('upload_max_filesize'))[0],
            'media_id' => $media->id
        ]);
    }

    public function storeChunk(UploadMediaChunkRequest $r): JsonResponse
    {
        try {
            $res = $this->service->storeChunk($r->dto());
            return response()->json(['filename' => $res]);
        } catch (MediaException $e) {
            $redisMedia = app()->make(MediaRedisRepository::class)
                ->incrementFailedAttempts($r->dto()->media_id);

            if ($redisMedia->failed_attempts >= 3) {
                $mediaRedisService = app()->make(MediaRedisService::class);
                $mediaRedisService->delete($r->dto()->media_id);

                $this->service->getModel()->destroy($r->dto()->media_id);
            }

            throw $e;
        }
    }

    public function destroy(): JsonResponse
    {
        return response()->json([
            'status' => 'success'
        ]);
    }
}
