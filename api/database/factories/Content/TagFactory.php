<?php

namespace Database\Factories\Content;

use App\Models\Content\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tag::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'title' => $this->faker->unique()->text(mt_rand(5, 30)),
            'body' => $this->faker->text,
            'status' => 'approved'
        ];
    }
}
