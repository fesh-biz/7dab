<?php

declare(strict_types=1);


namespace App\Plugins\Redis;


/**
 * Class Model
 * @package App\Plugins\Redis
 * @property int $id
 * @method search(string $field, mixed $value)
 * @method find(int $id)
 * @method delete(int $id)
 * @method deleteAll()
 * @method all()
 * @method deleteMultiple(array $ids)
 */
class Model
{
    protected array $attributes;
    protected Redis $redis;

    public function __construct()
    {
        $this->redis = new Redis($this->getRedisKey());
    }

    public function create(array $data): self
    {
        if (!array_key_exists('id', $data)) {
            throw new RedisException('Key id inside $data is required to create redis record');
        }

        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }

        $this->redis->create($this->id, $data);

        return $this;
    }

    private function getRedisKey(): string
    {
        $className = basename(str_replace('\\', '/', get_class($this)));
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $className));
    }

    public function __set($name, $value)
    {
        if ($name === 'id') $value = (int) $value;

        $this->attributes[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }

        return null;
    }

    public function __call($name, $arguments)
    {
        $id = null;
        if (count($arguments) === 1) {
            $id = $arguments[0];
        }

        $res = null;
        if (method_exists($this->redis, $name)) {
            $res = $this->redis->{$name}($id);
        }

        return $res;
    }

    public static function __callStatic($method, $arguments)
    {
        $model = new static();

        $id = null;
        if (count($arguments) === 1) {
            $id = $arguments[0];
        }

        return $model->{$method}($id);
    }
}
