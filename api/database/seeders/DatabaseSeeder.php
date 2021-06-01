<?php

namespace Database\Seeders;

use App\Models\User;
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
    }
}
