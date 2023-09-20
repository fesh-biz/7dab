<?php

namespace App\Services\Media;


use Illuminate\Http\UploadedFile;

class MediaFileService
{
    private array $patterns = ['<?php', 'phar'];

    public function checkFileHasExploits(string $fileContent)
    {
        foreach ($this->patterns as $pattern) {
            if (str_contains($fileContent, $pattern)) {
                throw new \Exception('File has exploit: ' . $pattern);
            }
        }
    }

    public function storeChunk(int $mediaId, UploadedFile $file): string
    {
        $path = 'media-' . $mediaId;
        $filename = $file->hashName();
        $file->storeAs($path, $filename, 'file_chunks_storage');

        return $filename;
    }
}
