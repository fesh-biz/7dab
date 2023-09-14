<?php

declare(strict_types=1);

namespace App\Data\Media;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class UploadMediaChunkData extends Data
{
    public function __construct(
        public int $media_id,
        public UploadedFile $file_chunk,
        public int $chunk_index,
        public int $total_chunks
    )
    {
    }
}
