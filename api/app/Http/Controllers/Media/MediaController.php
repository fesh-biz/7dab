<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Http\Resources\Media\MediaResource;
use App\Plugins\MimeDetector\MimeDetector;
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

    public function checkFile(Request $request): JsonResponse
    {
        $file = $request->file('file');

        if ($file->getSize() > 100) {
            throw new \Exception('The given file chunk is too large');
        }

        $imageMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $videoMimeTypes = ['video/mp4', 'video/webm'];

        $mimeDetector = new MimeDetector();
        $mimeDetector->setFile($file);
        $mimeType = $mimeDetector->getMimeType();

        if (!in_array($mimeType, $imageMimeTypes) && !in_array($mimeType, $videoMimeTypes)) {
            throw new \Exception('Invalid file type: ' . $mimeType);
        }

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
