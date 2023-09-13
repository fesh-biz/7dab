<?php

namespace App\Data\Media;

use Spatie\LaravelData\Data;

class CreateMediaData extends Data
{
    public function __construct(
        public int $user_id,
        public string $original_filename,
        public string $mime_type
    )
    {
    }
}
