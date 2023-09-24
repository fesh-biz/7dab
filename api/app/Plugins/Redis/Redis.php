<?php

declare(strict_types=1);

namespace App\Plugins\Redis;

use Predis\Client;

class Redis implements RedisInterface
{
    protected Client $client;
    protected string $key;

    public function __construct(string $key)
    {
        $this->client = new Client();

        $this->key = $key;
    }

    public function getWhere(string $field, mixed $value):? array
    {
        $all = $this->all();

        $res = [];
        foreach ($all as $id => $obj) {
            if ($obj->{$field} === $value) {
                $res[$id] = $obj;
            }
        }

        if (!count($res)) return null;

        return $res;
    }

    public function create(array $data)
    {
        if (!array_key_exists('id', $data)) {
            throw new RedisException('Key id inside $data is required to create redis record');
        }

        $data['created_at'] = now()->format('Y-m-d h:m:s');

        $id = $data['id'];
        $data = json_encode($data);
        $this->client->hset($this->key, (string)$id, $data);

        return $this->find($id);
    }

    public function update(int $id, array $data)
    {
        if (!$this->find($id)) {
            throw new RedisException('Model with $id=' . $id . ' not found');
        }

        $data['id'] = $id;

        $this->create($data);

        return $this->find($id);
    }

    public function all(): array
    {
        $res = $this->client->hgetall($this->key);

        foreach ($res as $id => $data) {
            $res[$id] = json_decode($data) ?? $data;
        }

        return $res;
    }

    public function delete(int $id): bool
    {
        if (!$id) {
            throw new RedisException('Argument $id was\'nt given');
        }

        return !!$this->client->hdel($this->key, [$id]);
    }

    public function deleteAll(): bool
    {
        $ids = [];
        foreach ($this->all() as $id => $data) {
            $ids[] = $id;
        }

        return $this->deleteMultiple($ids);
    }

    public function deleteMultiple(array $ids): bool
    {
        if (!$ids) {
            return false;
        }

        return !!$this->client->hdel($this->key, $ids);
    }

    public function find(int $id)
    {
        $res = $this->client->hget($this->key, (string)$id);

        if (!$res) {
            return null;
        }

        return json_decode($res);
    }
}
