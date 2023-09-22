<?php

declare(strict_types=1);

namespace App\Plugins\Redis;

use Predis\Client;

class Redis
{
    protected Client $client;
    protected string $key;

    public function __construct(string $key)
    {
        $this->client = new Client();

        $this->key = $key;
    }

    public function search(string $key, $value): array
    {
        $all = $this->all();

        $res = [];
        foreach ($all as $id => $obj) {
            if ($obj->{$key} === $value) {
                $res[$id] = $obj;
            }
        }

        return $res;
    }

    public function create(int $id, array $data)
    {
        $data['created_at'] = now()->format('Y-m-d h:m:s');
        $data = json_encode($data);

        $this->client->hset($this->key, (string)$id, $data);

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

    public function delete(int $id)
    {
        $this->client->hdel($this->key, [$id]);
    }

    public function deleteAll()
    {
        $ids = [];
        foreach ($this->all() as $id => $data) {
            $ids[] = $id;
        }

        $this->deleteMultiple($ids);
    }

    public function deleteMultiple(array $ids)
    {
        if (!$ids) {
            return;
        }

        $this->client->hdel($this->key, $ids);
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
