<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\CheckFileRequest;
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
            'media_id' => $media->id ?? null
        ]);
    }

    public function create(Request $request)
    {
        $fileContent = fopen($request->file, 'rb');
        $first6Bites = fread($fileContent, 6);

        ddh($first6Bites);
    }

    public function destroy(): JsonResponse
    {
        return response()->json([
            'status' => 'success'
        ]);
    }
}
