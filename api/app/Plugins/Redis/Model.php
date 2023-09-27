<?php

declare(strict_types=1);


namespace App\Plugins\Redis;


/**
 * Class Model
 * @package App\Plugins\Redis
 * @property int $id
 * @method create(array $data)
 * @method update(int $id, array $data)
 * @method delete(int $id = null)
 * @method deleteAll()
 * @method all()
 * @method deleteMultiple(array $ids)
 * @method getWhere(string $field, mixed $value)
 * @method find(int $id)
 */
class Model
{
    protected array $attributes;
    public Redis $redis;

    public function __construct()
    {
        $this->redis = new Redis($this->getRedisKey());
    }

    public function save()
    {
        $data = get_object_vars($this)['attributes'];

        $this->redis->update($this->id, $data);
    }

    private function checkMethodExists(string $name)
    {
        if (!method_exists($this->redis, $name)) {
            $className = get_class($this->redis);
            throw new RedisException("Method $name not found in $className");
        }
    }

    private static function makeResult(mixed $res): self|array|bool|null
    {
        if (!$res || is_bool($res)) {
            return $res;
        }

        if ($res->id ?? false) {
            return self::getModel($res);
        }

        if (is_array($res)) {
            $collection = [];
            foreach ($res as $id => $data) {
                $collection[$id] = self::getModel($data);
            }

            if (!count($collection)) {
                return null;
            }

            return $collection;
        }

        return null;
    }

    private static function getModel(mixed $attributes): ?self
    {
        if (!$attributes) {
            return null;
        }

        $attributes = get_object_vars($attributes);

        $model = new static();

        foreach ($attributes as $name => $value) {
            $model->{$name} = $value;
        }

        return $model;
    }

    private function getRedisKey(): string
    {
        $className = basename(str_replace('\\', '/', static::class));
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $className));
    }

    public function __set($name, $value)
    {
        if ($name === 'id') $value = (int)$value;

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
        $this->checkMethodExists($name);

        $res = null;
        $isExecuted = false;
        if ($name === 'delete') {
            $res = $this->redis->delete($arguments[0] ?? $this->id);
            $isExecuted = true;
        }

        if (!$isExecuted) {
            $res = $this->redis->{$name}($arguments[0] ?? null, $arguments[1] ?? null);
        }

        return self::makeResult($res);
    }

    public static function __callStatic($name, $arguments)
    {
        $model = new static();
        $model->checkMethodExists($name);

        $res = $model->redis->{$name}($arguments[0] ?? null, $arguments[1] ?? null);

        return self::makeResult($res);
    }
}
