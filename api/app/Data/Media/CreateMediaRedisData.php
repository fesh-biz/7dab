<?php
declare(strict_types=1);

namespace App\Data\Media;

use Spatie\LaravelData\Data;

class CreateMediaRedisData extends Data
{
    public function __construct(
        public int $id,
        public string $mime_type,
        public array $chunks = [],
        public int $failed_attempts = 0
    )
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'mime_type' => $this->mime_type,
            'chunks' => $this->chunks,
            'failed_attempts' => $this->failed_attempts
        ];
    }
}
