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
            'title' => $this->faker->text(mt_rand(5, 30)),
            'body' => $this->faker->text,
            'total_post_rating' => mt_rand(-100, 100),
            'total_posts' => mt_rand(0, 100)
        ];
    }
}
