<?php
declare(strict_types=1);

namespace App\Data\Media;

use Spatie\LaravelData\Data;

class UpdateMediaRedisData extends Data
{
    public function __construct(
        public int $id,
        public string $mimeType,
        public array $chunks
    )
    {
    }
}
