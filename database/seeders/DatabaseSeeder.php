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
            'name' => 'Wayan Treasurer',
            'email' => 'treasurer@banjar.com',
            'password' => 'password123', 
            'role' => 'treasurer',
        ]);

        User::create([
            'name' => 'Made Kelian',
            'email' => 'kelian@banjar.com',
            'password' => 'password123',
            'role' => 'kelian',
        ]);

        
        Category::create(['category_name' => 'Urunan (Cotisation)', 'type' => 'income']);
        Category::create(['category_name' => 'Patus (Décès)', 'type' => 'income']);
        Category::create(['category_name' => 'Desa Grant (Subvention)', 'type' => 'income']);
        
        Category::create(['category_name' => 'Cérémonie Odalan', 'type' => 'expense']);
        Category::create(['category_name' => 'Achat Matériel Banjar', 'type' => 'expense']);
        Category::create(['category_name' => 'Aide Sociale', 'type' => 'expense']);
    }
}