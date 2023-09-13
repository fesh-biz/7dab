<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Http\Requests\Media\CheckFileRequest;
use App\Http\Resources\Media\MediaResource;
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
        $media = $this->service->create($r->dto());

        return response()->json('success');
    }

    public function create(Request $request): MediaResource
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
