<?php

namespace Database\Seeders\Content;

use App\Models\Content\Post;
use App\Models\Content\Tag;
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
        }
    }
}
