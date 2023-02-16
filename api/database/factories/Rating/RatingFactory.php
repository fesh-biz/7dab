<?php

namespace Database\Factories\Rating;

use Illuminate\Database\Eloquent\Factories\Factory;

class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ratingable_id' => 1,
            'ratingable_type' => 'sds',
            'positive_votes' => mt_rand(10, 1000),
            'negative_votes' => mt_rand(10, 1000),
        ];
    }
}
