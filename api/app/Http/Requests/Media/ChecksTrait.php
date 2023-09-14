<?php

namespace App\Http\Requests\Media;

use App\Plugins\MimeDetector\MimeDetector;
use Illuminate\Validation\ValidationException;

trait ChecksTrait
{
    private function getFileType($file): string
    {
        $mimeDetector = new MimeDetector();
        $mimeDetector->setFile($file);
        return $mimeDetector->getMimeType();
    }

    private function checkMimeType(string $mimeType): void
    {
        $imageMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $videoMimeTypes = ['video/mp4', 'video/webm'];

        if (!in_array($mimeType, $imageMimeTypes) && !in_array($mimeType, $videoMimeTypes)) {
            $this->throwFileValidationException(trans('validation.wrong_file_type'));
        }
    }

    private function throwFileValidationException(string $message): void
    {
        throw ValidationException::withMessages(['file_chunk' => $message]);
    }
}
