<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Content\CommentSeeder;
use Database\Seeders\Content\PostSeeder;
use Database\Seeders\Content\TagSeeder;
use File;
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
        $this->call(RoleSeeder::class);
        
        User::factory()->create([
            'email' => 'feshbiz@gmail.com',
            'login' => 'uvei',
            'password' => bcrypt('Fesh761737'),
            'role_id' => 1
        ]);
        
        User::factory()->create([
            'email' => 'svitluk.vargetova@gmail.com',
            'login' => 'Світлана',
            'password' => bcrypt('vargetova1990'),
            'role_id' => 2
        ]);
        
        User::factory(5)->create();
        
        $this->call(PassportSeeder::class);
        
        if (app()->environment() === 'dusk') {
            return;
        }
        
        if (File::exists(config('7dab.post_image_storage_base_path'))) {
            File::deleteDirectory(config('7dab.post_image_storage_base_path'));
        }
        
        // Content
        $this->call(TagSeeder::class);
        $this->call(PostSeeder::class);
        $this->call(CommentSeeder::class);
        $this->call(RatingSeeder::class);
    }
}
