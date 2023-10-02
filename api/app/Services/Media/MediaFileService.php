<?php

namespace App\Services\Media;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MediaFileService
{
    private string $chunksBasePath;
    private string $mergedFilesBasePath;
    private array $exploitPatterns = ['<?php', 'phar'];

    public function __construct()
    {
        $this->chunksBasePath = storage_path('file-chunks');
        $this->mergedFilesBasePath = storage_path('merged-files');
    }

    /**
     * Returns filename for the merged file from the given chunks
     * @param int $mediaId
     * @param string $mimeType
     * @param array $chunks
     * @return string
     * @throws \Exception
     */
    public function mergeFileChunks(int $mediaId, string $mimeType, array $chunks): string
    {
        $chunksPath = $this->chunksBasePath . "/media-$mediaId/";

        $dir = $this->prepareFolder($mediaId);
        $hashName = Str::random(40);
        $extension = explode('/', $mimeType)[1];
        $filename = $dir . '/' . $hashName . ".$extension";

        foreach ($chunks as $i => $chunk) {
            $chunkPath = $chunksPath . $chunk->filename;
            $content = file_get_contents($chunkPath);
            if ($content === false) {
                throw new MediaException('Chunk is missing: ' . $chunksPath);
            }

            file_put_contents($filename, $content, FILE_APPEND);
        }

        return $filename;
    }

    public function checkFileHasExploits(string $fileContent)
    {
        foreach ($this->exploitPatterns as $pattern) {
            if (str_contains($fileContent, $pattern)) {
                throw new MediaException('File has exploit: ' . $pattern);
            }
        }
    }

    public function storeChunk(int $mediaId, UploadedFile $file): string
    {
        $dir = $this->createMediaChunksDirectory($mediaId);

        $path = 'media-' . $mediaId;
        $filename = $file->hashName();

        $file->storeAs($path, $filename, 'file_chunks_storage');

        return $filename;
    }

    public function deleteMediaChunksDirectory(int $mediaId): string
    {
        $dir = $this->chunksBasePath . "/media-$mediaId";

        File::deleteDirectory($dir);

        return $dir;
    }

    public function createMediaChunksDirectory(int $mediaId): string
    {
        $dir = $this->chunksBasePath . "/media-$mediaId";

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
            chmod($this->chunksBasePath, 0777);
            chmod($dir, 0777);
        }

        return $dir;
    }

    public function prepareFolder(int $mediaId): string
    {
        $dir = $this->mergedFilesBasePath . "/media-$mediaId";

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        return $dir;
    }
}
