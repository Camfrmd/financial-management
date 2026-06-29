<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // INCOME
        $communalDues = Category::create(['category_name' => 'Communal Dues (Iuran/Urunan)', 'type' => 'income']);
        Category::create(['category_name' => 'Urunan Wajib', 'type' => 'income', 'parent_id' => $communalDues->category_id]);
        Category::create(['category_name' => 'Peturunan Karya', 'type' => 'income', 'parent_id' => $communalDues->category_id]);

        $penalties = Category::create(['category_name' => 'Penalties (Denda)', 'type' => 'income']);
        Category::create(['category_name' => 'Denda Absen', 'type' => 'income', 'parent_id' => $penalties->category_id]);
        Category::create(['category_name' => 'Denda Keterlambatan', 'type' => 'income', 'parent_id' => $penalties->category_id]);

        $grants = Category::create(['category_name' => 'Grants & Donations (Bantuan & Dana Punia)', 'type' => 'income']);
        Category::create(['category_name' => 'Dana Desa', 'type' => 'income', 'parent_id' => $grants->category_id]);
        Category::create(['category_name' => 'Dana Punia', 'type' => 'income', 'parent_id' => $grants->category_id]);

        $commercial = Category::create(['category_name' => 'Commercial Revenues (Pendapatan Lain)', 'type' => 'income']);
        Category::create(['category_name' => 'Sewa Fasilitas', 'type' => 'income', 'parent_id' => $commercial->category_id]);
        Category::create(['category_name' => 'BUMDes / Usaha Banjar', 'type' => 'income', 'parent_id' => $commercial->category_id]);

        // EXPENSES
        $religious = Category::create(['category_name' => 'Religious Ceremonies (Upacara/Yadnya)', 'type' => 'expense']);
        Category::create(['category_name' => 'Piodalan', 'type' => 'expense', 'parent_id' => $religious->category_id]);
        Category::create(['category_name' => 'Pecaruan', 'type' => 'expense', 'parent_id' => $religious->category_id]);
        Category::create(['category_name' => 'Ogoh-Ogoh', 'type' => 'expense', 'parent_id' => $religious->category_id]);

        $infrastructure = Category::create(['category_name' => 'Infrastructure (Pembangunan & Pemeliharaan)', 'type' => 'expense']);
        Category::create(['category_name' => 'Perbaikan Pura', 'type' => 'expense', 'parent_id' => $infrastructure->category_id]);
        Category::create(['category_name' => 'Perbaikan Bale Banjar', 'type' => 'expense', 'parent_id' => $infrastructure->category_id]);

        $operational = Category::create(['category_name' => 'Operational & Administrative (Operasional)', 'type' => 'expense']);
        Category::create(['category_name' => 'Konsumsi Paruman', 'type' => 'expense', 'parent_id' => $operational->category_id]);
        Category::create(['category_name' => 'ATK', 'type' => 'expense', 'parent_id' => $operational->category_id]);
        Category::create(['category_name' => 'Listrik & Air', 'type' => 'expense', 'parent_id' => $operational->category_id]);

        $social = Category::create(['category_name' => 'Social & Welfare (Sosial & Kemanusiaan)', 'type' => 'expense']);
        Category::create(['category_name' => 'Santunan Kematian', 'type' => 'expense', 'parent_id' => $social->category_id]);
        Category::create(['category_name' => 'Bantuan Sakit', 'type' => 'expense', 'parent_id' => $social->category_id]);
    }
}
