<?php

namespace App\Redis\Repositories;

use App\Plugins\Redis\Model;
use App\Plugins\Redis\RedisException;

/**
 * Class Repository
 * @package App\Redis\Repositories
 * @mixin Model
 */
class Repository
{
    public function __call($name, $arguments)
    {
        return $this->model->{$name}($arguments[0] ?? null, $arguments[1] ?? null);
    }
}
