<?php

namespace App\Repository\Content;

use App\Models\Content\PostImage;

class PostImageRepository
{
    protected PostImage $model;

    public function __construct(PostImage $model)
    {
        $this->model = $model;
    }

    public function getModel(): PostImage
    {
        return $this->model;
    }

    public function create(int $postId, int $order, array $data): PostImage
    {
        return $this->model->create([
            'post_id' => $postId,
            'order' => $order,
            'original_filename' => $data['original_filename'],
            'title' => $data['title'] ?? null,
            'recognized_text' => null,
            'original_file_path' => $data['original_file_path'],
            'desktop_file_path' => $data['desktop_file_path'] ?? null,
            'mobile_file_path' => $data['mobile_file_path'] ?? null,
            'data' => json_encode($data['data']),
        ]);
    }
}
