<?php

namespace App\Repositories\Content;

use App\Models\Content\PostText;

class PostTextRepository
{
    protected PostText $model;

    public function __construct(PostText $model)
    {
        $this->model = $model;
    }

    public function getModel(): PostText
    {
        return $this->model;
    }

    public function create(int $postId, int $order, string $body): PostText
    {
        $body = str_replace("\n", '<br>', $body);

        return $this->model->create([
            'post_id' => $postId,
            'order' => $order,
            'body' => $body
        ]);
    }

    public function update(int $id, array $data): PostText
    {
        $data['body'] = str_replace("\n", '<br>', $data['body']);

        $model = $this->model->findOrFail($id);
        $model->update($data);

        return $model;
    }
}
