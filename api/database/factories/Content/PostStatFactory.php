<?php

namespace Database\Factories\Content;

use App\Models\Content\PostStat;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostStatFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PostStat::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'post_id' => 1,
            'views' => mt_rand(10, 10000),
            'positive_votes' => mt_rand(10, 10000),
            'negative_votes' => mt_rand(10, 10000),
            'comments' => mt_rand(10, 10000)
        ];
    }
}
