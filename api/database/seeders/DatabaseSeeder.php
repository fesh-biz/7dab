<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Content\CommentSeeder;
use Database\Seeders\Content\PostSeeder;
use Database\Seeders\Content\TagSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'user@app',
            'name' => 'user',
            'password' => bcrypt('password')
        ]);

        $this->call(PassportSeeder::class);

        // Content
        $this->call(TagSeeder::class);
        $this->call(PostSeeder::class);
        $this->call(CommentSeeder::class);
    }
}
