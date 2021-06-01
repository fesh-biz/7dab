<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PassportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $privateKey = base_path('storage/oauth-private.key');
        $publicKey = base_path('storage/oauth-public.key');

        if (file_exists($privateKey)){
            unlink($privateKey);
        }
        if (file_exists($publicKey)){
            unlink($publicKey);
        }

        copy(__DIR__ . '/passport-keys/oauth-private.key', $privateKey);
        copy(__DIR__ . '/passport-keys/oauth-public.key', $publicKey);

        $clients = [
            [
                'id' => 1,
                'name' => 'Laravel Personal Access Client',
                'secret' => 'Nymq7bDcyX4IuIevoCoFdV04GUl6CmrZRMLzQbYe',
                'redirect' => 'http://localhost',
                'personal_access_client' => 1,
                'provider' => null,
                'password_client' => 0,
                'revoked' => 0,
            ],
            [
                'id' => 2,
                'name' => 'Laravel Password Grant Client',
                'secret' => 'sFKCIaMm0CcttGi21C2d3nYfzebevgB4owYad5SM',
                'redirect' => 'http://localhost',
                'personal_access_client' => 0,
                'provider' => 'users',
                'password_client' => 1,
                'revoked' => 0,
            ]
        ];
        \DB::table('oauth_clients')->insert($clients);
    }
}
