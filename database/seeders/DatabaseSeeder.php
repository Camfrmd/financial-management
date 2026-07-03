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
        User::firstOrCreate(
            ['email' => 'treasurer@banjar.com'],
            [
                'username' => 'Wayan Treasurer',
                'password' => 'password123', 
                'role' => 'treasurer',
            ]
        );

        User::firstOrCreate(
            ['email' => 'kelian@banjar.com'],
            [
                'username' => 'Made Kelian',
                'password' => 'password123',
                'role' => 'kelian',
            ]
        );

        $this->call([
            CategorySeeder::class,
            FundSeeder::class,
        ]);
    }
}