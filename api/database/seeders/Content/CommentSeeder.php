<?php

namespace Database\Seeders\Content;

use App\Models\Content\Comment;
use App\Models\Content\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = Post::orderBy('id', 'desc')->limit(1)->get();

        $comments = $this->seedComments($posts->toArray(), Post::class);
        
        foreach (range(1, 4) as $level) {
            $comments = $this->seedComments($comments, Comment::class);
        }
    }

    private function seedComments(array $commentables, string $commentableType): array
    {
        $comments = [];
        foreach ($commentables as $commentable) {
            $createdComments = Comment::factory(mt_rand(2, 3))->create([
                'commentable_id' => $commentable['id'],
                'commentable_type' => $commentableType,
            ])->toArray();

            $comments = array_merge($comments, $createdComments);
        }

        return $comments;
    }
}
