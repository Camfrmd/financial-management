<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Fund;
use App\Models\Transaction;
use App\Models\Activity;
use App\Models\Category;
use App\Models\CommunityGroup;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_dashboard()
    {
        $this->withoutVite();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
    }

    public function test_dashboard_displays_correct_data()
    {
        $this->withoutVite();
        $user = User::factory()->create();
        
        // Arrange
        $group = CommunityGroup::create(['group_name' => 'Banjar Adat', 'description' => 'Test Group']);
        $activity1 = Activity::create([
            'activity_name' => 'Past Activity',
            'start_date' => now()->subDays(10),
            'end_date' => now()->subDays(5),
            'status' => 'completed'
        ]);
        $activity2 = Activity::create([
            'activity_name' => 'Upcoming Activity',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(15),
            'status' => 'planned'
        ]);

        $fund1 = Fund::create([
            'group_id' => $group->group_id,
            'activity_id' => $activity1->activity_id,
            'name' => 'Main Fund',
            'current_balance' => 1500000,
            'description' => 'Main operations'
        ]);
        Fund::create([
            'group_id' => $group->group_id,
            'activity_id' => $activity1->activity_id,
            'name' => 'Secondary Fund',
            'current_balance' => 500000,
            'description' => 'Secondary'
        ]);

        $parentCategory = Category::create([
            'category_name' => 'General Parent',
            'type' => 'income'
        ]);
        $category = Category::create([
            'category_name' => 'General',
            'type' => 'income',
            'parent_id' => $parentCategory->category_id
        ]);

        Transaction::create([
            'category_id' => $category->category_id,
            'fund_id' => $fund1->fund_id,
            'user_id' => $user->user_id,
            'type' => 'income',
            'amount' => 100000,
            'date' => now()->subDays(1),
            'description' => 'Test Income',
            'validation_status' => 'validated',
            'validated_by' => $user->user_id
        ]);

        // Act
        $response = $this->actingAs($user)->get('/dashboard');

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('totalBalance', 2100000); // 1.5M + 0.5M + 100K from transaction
        
        $recentTransactions = $response->viewData('recentTransactions');
        $this->assertCount(1, $recentTransactions);
        $this->assertEquals('Test Income', $recentTransactions->first()->description);

        $upcomingActivities = $response->viewData('upcomingActivities');
        $this->assertCount(1, $upcomingActivities);
        $this->assertEquals('Upcoming Activity', $upcomingActivities->first()->activity_name);
    }
}
