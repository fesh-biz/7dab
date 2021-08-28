<?php

namespace Database\Factories\Content;

use App\Models\Content\PostImage;
use App\Services\Content\PostImageService;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PostImage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $originalFilename = mt_rand(1, 10) . '.jpg';
        $imagePath = base_path('/database/fake-images/' . $originalFilename);

        $postImageService = app(PostImageService::class);
        $imageAttributes = $postImageService
            ->saveImageFile($imagePath);

        return [
            'post_id' => 1,
            'order' => 1,
            'original_filename' => $originalFilename,
            'title' => mt_rand(0, 1) ? $this->faker->text(mt_rand(30, 250)) : null,
            'recognized_text' => mt_rand(0, 1) ? $this->faker->text(mt_rand(30, 250)) : null,
            'original_file_path' => $imageAttributes['original_file_path'],
            'desktop_file_path' => $imageAttributes['desktop_file_path'] ?? null,
            'mobile_file_path' => $imageAttributes['mobile_file_path'] ?? null,
            'data' => json_encode($imageAttributes['data']),
        ];
    }
}
