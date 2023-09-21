<?php

declare(strict_types=1);

namespace App\Data\User;

use Spatie\LaravelData\Data;

class UserRedisData extends Data
{
    public function __construct(
        public int $id,
        public array $media_ids = []
    )
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'media_ids' => $this->media_ids
        ];
    }
}
