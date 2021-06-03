<?php

namespace Database\Seeders\Content;

use App\Models\Content\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

            Tag::factory(30)->create();
    }
}
