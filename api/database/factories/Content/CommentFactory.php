<?php

namespace Database\Factories\Content;

use App\Models\Content\Comment;
use App\Models\Content\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'user_id' => mt_rand(1, 7),
            'commentable_id' => 1,
            'commentable_type' => Post::class,
            'body' => $this->faker->text,
            'post_id' => 1
        ];
    }
}
