<?php

namespace Database\Factories\Media;

use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
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
            'original_filename' => 'test.jpg',
            'mime_type' => 'image/jpg'
        ];
    }
}
