<?php

namespace Database\Factories\Content;

use App\Models\Content\PostText;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostTextFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PostText::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'post_id' => 1,
            'order' => 1,
            'body' => $this->faker->text(mt_rand(400, 1000)),
        ];
    }
}
