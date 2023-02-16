<?php

namespace Database\Seeders;

use App\Models\Content\Post;
use App\Models\Rating\Rating;
use App\Models\User;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (User::all() as $user) {
            Rating::factory()->create([
                'ratingable_id' => $user->id,
                'ratingable_type' => User::class
            ]);
        }
        
        foreach (Post::all() as $post) {
            Rating::factory()->create([
                'ratingable_id' => $post->id,
                'ratingable_type' => Post::class
            ]);
        }
    }
}
