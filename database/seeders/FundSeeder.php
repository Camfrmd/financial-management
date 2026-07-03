<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fund;
use App\Models\CommunityGroup;
use App\Models\Activity;

class FundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Création d'un groupe communautaire par défaut
        $group = CommunityGroup::firstOrCreate(
            ['group_name' => 'Banjar Adat'],
            ['description' => 'Groupe principal du Banjar']
        );

        // 2. Création d'une activité par défaut
        $activity = Activity::firstOrCreate(
            ['activity_name' => 'Operasional Banjar'],
            [
                'status' => 'ongoing',
                'start_date' => now(),
                'end_date' => now()->addYears(10),
            ]
        );

        // 3. Création des fonds (avec et sans activité/groupe)
        Fund::firstOrCreate(
            ['name' => 'Kas Umum Banjar'],
            [
                'description' => 'Fonds général du Banjar (non lié à une activité spécifique)',
                'current_balance' => 0,
                'group_id' => null,
                'activity_id' => null,
            ]
        );

        Fund::firstOrCreate(
            ['name' => 'Kas Pembangunan'],
            [
                'description' => 'Cagnotte dédiée à la construction et rénovation',
                'current_balance' => 0,
                'group_id' => $group->group_id,
                'activity_id' => $activity->activity_id,
            ]
        );
    }
}
