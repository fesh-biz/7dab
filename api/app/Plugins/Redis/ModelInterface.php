<?php

namespace App\Plugins\Redis;

interface ModelInterface
{
    public function getWhere();

    public function create();

    public function all();

    public function delete();

    public function deleteAll();

    public function deleteMultiple();

    public function find();
}
