<?php

namespace Database\Factories\Rating;

use Illuminate\Database\Eloquent\Factories\Factory;

class RatingVoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'ratingable_id' => 1,
            'ratingable_type' => 'sds',
            'is_positive' => mt_rand(0, 1),
        ];
    }
}
