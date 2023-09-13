<?php

namespace App\Redis\Models;

use App\Redis\Redis;

class MediaRedis
{
    protected Redis $redis;

    public function __construct(Redis $redis)
    {

    }


    public function create(int $id, array $data): int
    {
        return parent::create($id, $data);
    }
}
