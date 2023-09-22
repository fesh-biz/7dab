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
 * @method create(array $data)
 */
class Model
{
    protected array $attributes;
    protected Redis $redis;

    public function __construct()
    {
        $this->redis = new Redis($this->getRedisKey());
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

        if ($name === 'delete') {
            $this->redis->delete($this->id);

            return $this;
        }

        $res = null;
        if (method_exists($this->redis, $name)) {
            $res = $this->redis->{$name}($id);
        }

        return $res;
    }

    public static function __callStatic($name, $arguments)
    {
        $model = new static();

        $id = null;
        if (count($arguments) === 1) {
            $id = $arguments[0];
        }

        if ($name === 'delete') {
            if (!$id) {
                throw new RedisException('Argument $id was\'nt given');
            }

            $model->redis->delete($id);

            return true;
        }


        $data = null;
        if (count($arguments) === 1) {
            $data = $arguments[0];
        }

        $res = $model->{$name}($data);

        if (is_array($res)) {
            $collection = [];

            foreach ($res as $attributes) {
                $collection[] = self::getModel(get_object_vars($attributes));
            }

            return $collection;
        }

        if ($res->id ?? false) {
            return self::getModel(get_object_vars($res));
        }

        return $res;
    }

    private static function getModel(array $attributes): self
    {
        $model = new static();

        foreach ($attributes as $name => $value) {
            $model->attributes[$name] = $value;
        }

        return $model;
    }
}
