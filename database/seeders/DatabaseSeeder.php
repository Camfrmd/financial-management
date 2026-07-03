<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'username' => 'Wayan Treasurer',
            'email' => 'treasurer@banjar.com',
            'password' => 'password123', 
            'role' => 'treasurer',
        ]);

        User::create([
            'username' => 'Made Kelian',
            'email' => 'kelian@banjar.com',
            'password' => 'password123',
            'role' => 'kelian',
        ]);

        $this->call([
            CategorySeeder::class,
        ]);
    }
}