<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\CheckFileRequest;
use App\Http\Requests\Media\UploadMediaChunkRequest;
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

    }

    public function destroy(): JsonResponse
    {
        return response()->json([
            'status' => 'success'
        ]);
    }
}
