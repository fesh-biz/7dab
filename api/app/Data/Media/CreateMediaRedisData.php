<?php
declare(strict_types=1);

namespace App\Data\Media;

use Spatie\LaravelData\Data;

class CreateMediaRedisData extends Data
{
    public function __construct(
        public int $id,
        public string $mimeType
    )
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'mime_type' => $this->mimeType,
            'chunks' => []
        ];
    }
}
