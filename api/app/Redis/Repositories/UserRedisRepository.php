<?php

namespace App\Redis\Repositories;

use App\Data\User\UserRedisData;
use App\Redis\Models\UserRedis;

class UserRedisRepository extends Repository
{
    public function __construct(
        protected UserRedis $model
    )
    {
    }

    public function create(UserRedisData $data)
    {
        return $this->model->create($data->toArray());
    }

    public function update(int $id, UserRedisData $data)
    {
        return $this->model->update($id, $data->toArray());
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
