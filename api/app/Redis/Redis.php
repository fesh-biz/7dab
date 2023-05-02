<?php

namespace App\Redis;

use Predis\Client;

class Redis
{
    protected Client $client;
    protected string $key;
    
    public function __construct(Client $client)
    {
        $this->client = $client;
        
        $this->key = $this->getKey();
    }
    
    public function search(string $key, $needle): array
    {
        $all = $this->all();
        
        $res = [];
        foreach ($all as $id => $obj) {
            if ($obj->{$key} === $needle) {
                $res[$id] = $obj;
            }
        }
        
        return $res;
    }
    
    public function create(int $id, array $data)
    {
        $data = json_encode($data);
        
        $this->client->hset($this->key, $id, $data);
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
        $this->client->hdel($this->key, $ids);
    }
    
    public function find(int $id)
    {
        $res = $this->client->hget($this->key, $id);
        
        return json_decode($res) ?? $res;
    }
    
    public function getKey(): string
    {
        $className = basename(str_replace('\\', '/', static::class));
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $className));
    }
}