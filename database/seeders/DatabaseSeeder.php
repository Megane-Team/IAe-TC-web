<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $apiUrl = config('app.api_url');

        // make an post request to api/users
        $response = Http::post($apiUrl . '/users/default', [
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'password',
            'role' => 'admin',
            'nik' => '000000',
        ]);

        if ($response->successful()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'nik' => '000000',
            ]);
    
            echo 'User created successfully';
            echo 'Email: admin@gmail.com';
            echo 'Password: password';
        } else {
            echo 'Failed to create user or duplicate user';
        }
    }
}
