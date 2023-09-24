<?php

namespace App\Plugins\Redis;

interface RedisInterface
{
    public function getWhere(string $field, mixed $value);

    public function create(array $data);

    public function all();

    public function delete(int $id): bool;

    public function deleteAll(): bool;

    public function deleteMultiple(array $ids): bool;

    public function find(int $id);
}
