<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Fund;
use App\Models\CommunityGroup;
use App\Models\Activity;

class FundTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_a_fund_with_group_and_activity()
    {
        $group = CommunityGroup::create([
            'group_name' => 'Banjar Utara',
            'description' => 'Northern community group',
        ]);

        $activity = Activity::create([
            'activity_name' => 'Nyepi Preparation',
            'start_date' => now(),
            'end_date' => now()->addDays(5),
            'status' => 'planned',
        ]);

        $fund = Fund::create([
            'group_id' => $group->group_id,
            'activity_id' => $activity->activity_id,
            'name' => 'Nyepi Fund',
            'current_balance' => 500000,
            'description' => 'Funds for Nyepi',
        ]);

        $this->assertDatabaseHas('funds', [
            'name' => 'Nyepi Fund',
            'current_balance' => 500000,
            'group_id' => $group->group_id,
            'activity_id' => $activity->activity_id,
        ]);

        $this->assertEquals('Banjar Utara', $fund->group->group_name);
        $this->assertEquals('Nyepi Preparation', $fund->activity->activity_name);
    }
}
