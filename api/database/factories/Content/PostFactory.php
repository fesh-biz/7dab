<?php

namespace Database\Factories\Content;

use App\Models\Content\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    public function definition()
    {
        return [
            'user_id' => 1,
            'title' => $this->faker->text(50),
            'status' => ['pending', 'reviewing', 'approved', 'declined', 'editing'][mt_rand(0, 4)]
        ];
    }
}
