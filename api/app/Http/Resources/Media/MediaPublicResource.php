<?php

namespace App\Http\Resources\Media;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaPublicResource extends JsonResource
{
    public function toArray($request): array
    {
        $data = json_decode($this->data);

        return [
            'id' => $this->id,
            'mediable_id' => $this->mediable_id,
            'mediable_type' => $this->mediable_type,
            'order' => $this->order,
            'title' => $this->title,
            'mime_type' => $this->mime_type,
            'data' => $this->data,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
