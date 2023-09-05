<?php

namespace App\Http\Resources\Media;

use App\Models\Media\Media;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Media
 */
class MediaResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'mediable_id' => $this->mediable_id,
            'mediable_type' => $this->mediable_type,
            'user_id' => $this->user_id,
            'order' => $this->order,
            'original_filename' => $this->original_filename,
            'title' => $this->title,
            'original_size' => $this->original_size,
            'mime_type' => $this->mime_type,
        ];
    }
}
