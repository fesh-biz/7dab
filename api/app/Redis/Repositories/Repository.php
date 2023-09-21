<?php

namespace App\Redis\Repositories;

/**
 * Class Repository
 * @package App\Redis\Repositories
 * @method find(int $id)
 * @method delete(int $id)
 * @method deleteAll()
 * @method all()
 * @method deleteMultiple(array $ids)
 */
class Repository
{
    public function __call($name, $arguments)
    {
        $id = null;
        if (count($arguments) === 1) {
            $id = $arguments[0];
        }
        $res = null;
        if (method_exists($this->model, $name)) {
            $res = $this->model->{$name}($id);
        }

        return $res;
    }
}
