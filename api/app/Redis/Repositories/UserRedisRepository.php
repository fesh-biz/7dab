<?php

declare(strict_types=1);

namespace App\Redis\Repositories;

use App\Data\User\UserRedisData;
use App\Redis\Models\UserRedis;

class UserRedisRepository extends Repository
{
    protected UserRedis $model;

    public function __construct(UserRedis $model)
    {
        $this->model = $model;
    }

    public function create(UserRedisData $data)
    {
        $this->model->create($data->id, $data->toArray());
    }

    public function update(UserRedisData $data)
    {
        $this->model->create($data->id, $data->toArray());
    }

    public function addMediaId(int $id, int $mediaId)
    {
        $user = $this->model->find($id);
        $user->media_ids[] = $mediaId;

        $userData = UserRedisData::from($user);

        $this->update($userData);
    }

    public function deleteMediaId(int $id, int $mediaId)
    {
        $user = $this->model->find($id);

        foreach ($user->media_ids as $key => $id) {
            if ($id === $mediaId) {
                unset($user->media_ids[$key]);
                break;
            }
        }

        $this->update(UserRedisData::from($user));
    }
}
