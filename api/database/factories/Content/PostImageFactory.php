<?php

namespace Database\Factories\Content;

use App\Models\Content\PostImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PostImage::class;

    protected array $images = [
        1 => [
            'width' => 1000,
            'height' => 450,
            'size_kb' => 28
        ],
        2 => [
            'width' => 1680,
            'height' => 1050,
            'size_kb' => 242
        ],
        3 => [
            'width' => 604,
            'height' => 340,
            'size_kb' => 49
        ],
        4 => [
            'width' => 900,
            'height' => 1203,
            'size_kb' => 482
        ],
        5 => [
            'width' => 1920,
            'height' => 1200,
            'size_kb' => 289
        ],
        6 => [
            'width' => 2500,
            'height' => 1172,
            'size_kb' => 235
        ],
        7 => [
            'width' => 1400,
            'height' => 2150,
            'size_kb' => 1640
        ],
        8 => [
            'width' => 1000,
            'height' => 1778,
            'size_kb' => 482
        ],
        9 => [
            'width' => 442,
            'height' => 600,
            'size_kb' => 111
        ],
        10 => [
            'width' => 183,
            'height' => 275,
            'size_kb' => 8
        ],
    ];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $imageIndex = mt_rand(1, 10);
        $filename = \Str::random(16) . '.jpg';
        \File::copy(
            base_path('database/fake-images') . "/${imageIndex}.jpg",
            config('7dab.post_original_images_folder') . "/${filename}"
        );

        return [
            'post_id' => 1,
            'order' => 1,
            'original_filename' => $this->faker->text,
            'title' => mt_rand(0, 1) ? $this->faker->text(mt_rand(30, 250)) : null,
            'recognized_text' => mt_rand(0, 1) ? $this->faker->text(mt_rand(30, 250)) : null,
            'filename' => $filename,
            'original_width' => $this->images[$imageIndex]['width'],
            'original_height' => $this->images[$imageIndex]['height'],
            'original_size_kb' => $this->images[$imageIndex]['size_kb'],
        ];
    }
}
