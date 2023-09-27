<?php

namespace Tests\Unit\Redis;

use App\Plugins\Redis\Model;
use PHPUnit\Framework\TestCase;

class RedisTestModel extends Model {}

class ModelTest extends TestCase
{
    protected RedisTestModel $model;

    /**
     * @test
     * @group RedisModel
     */
    public function model_takes_redis_key_from_static_class()
    {
        $this->prepareTest();

        $this->assertTrue($this->model->redis->getRedisKey() === 'redis_test_model', 'Redis key mismatch');
    }

    /**
     * @test
     * @group RedisModel
     */
    public function model_creates_correct_id_for_new_record()
    {
        $this->prepareTest();

        $data = $this->getModelData();

        $this->model->create($data);

        $this->assertArrayHasKey($data['id'], $this->model->all());
    }

    /**
     * @test
     * @group RedisModel
     */
    public function model_can_create_redis_record()
    {
        $this->prepareTest();

        $data = $this->getModelData();

        $createdModel = $this->model->create($data);

        $redisModel = $this->model->all()[$data['id']];

        foreach ($data as $key => $value) {
            $this->assertEquals($createdModel->{$key}, $redisModel->{$key});
        }
    }

    /**
     * @test
     * @group RedisModel
     */
    public function model_can_create_redis_record_statically()
    {
        $this->prepareTest();

        $data = $this->getModelData(23);

        $createdModel = RedisTestModel::create($data);

        $redisModel = $this->model->all()[$data['id']];

        foreach ($data as $key => $value) {
            $this->assertEquals($createdModel->{$key}, $redisModel->{$key});
        }
    }

    // /**
    //  * @test
    //  * @group RedisModel
    //  */
    // public function model_can_update_redis_data()
    // {
    //     $this->prepareTest();
    //
    //     $id = 23;
    //
    // }

    private function getModelData(int $id = 1): array
    {
        return [
            'id' => $id,
            'first' => 'Test Value'
        ];
    }

    private function getRedisTestModel(): RedisTestModel
    {
        return app()->make(RedisTestModel::class);
    }

    private function prepareTest()
    {
        $this->model = $this->getRedisTestModel();
        $this->model->deleteAll();
    }
}
