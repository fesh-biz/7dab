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
        Tag::factory()->create(['title' => 'Україна']);
        Tag::factory()->create(['title' => 'Біла Церква']);
        Tag::factory()->create(['title' => 'Ігри']);
        Tag::factory()->create(['title' => 'Українська Промисловість']);
        Tag::factory()->create(['title' => 'Українська Музика']);
        Tag::factory()->create(['title' => 'Війна']);
    }
}
