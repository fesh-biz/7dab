<?php

namespace App\Redis\Repositories;

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
