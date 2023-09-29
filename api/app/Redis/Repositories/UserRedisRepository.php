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

    public function create(UserRedisData $data): UserRedis
    {
        return $this->model->create($data->toArray());
    }

    public function update(int $id, UserRedisData $data)
    {
        return $this->model->update($id, $data->toArray());
    }

    public function addMediaId(int $id, int $mediaId): UserRedis
    {
        /** @var UserRedis $user */
        $user = $this->model->find($id);

        $ids = $user->media_ids;
        $ids[] = $mediaId;
        $user->media_ids = $ids;

        $user->save();

        return $user;
    }

    public function deleteMediaId(int $id, int $mediaId): UserRedis
    {
        /** @var UserRedis $user */
        $user = $this->model->find($id);

        $mediaIds = $user->media_ids;
        foreach ($mediaIds as $key => $id) {
            if ($id === $mediaId) {
                unset($mediaIds[$key]);
                break;
            }
        }

        $user->media_ids = $mediaIds;
        $user->save();

        return $user;
    }

    public function deleteUserIfEmptyMediaIds(int $userId)
    {
        /** @var UserRedis $user */
        $user = $this->find($userId);

        if (!count($user->media_ids)) {
            $user->delete();
        }
    }
}
