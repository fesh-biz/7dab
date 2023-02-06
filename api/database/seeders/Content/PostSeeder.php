<?php

namespace Database\Seeders\Content;

use App\Models\Content\Post;
use App\Models\Content\PostImage;
use App\Models\Content\PostText;
use App\Models\Content\Tag;
use File;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = Tag::all();

        $posts = Post::factory(30)->create();

        foreach ($posts as $post) {
            $post->tags()->attach($tags->random(mt_rand(1, 6))->pluck('id'));
            $this->createPostBody($post->id);
        }
    }

    private function createPostBody(int $postId): void
    {
        foreach (range(1, mt_rand(1, 10)) as $order) {
            if (mt_rand(0, 1)) {
                PostImage::factory()->create([
                    'post_id' => $postId,
                    'order' => $order
                ]);
            } else {
                PostText::factory()->create([
                    'post_id' => $postId,
                    'order' => $order
                ]);
            }
        }
    }
}
