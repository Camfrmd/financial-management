<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\MemberContribution;
use App\Models\Member;
use App\Models\Transaction;
use App\Models\Fund;
use App\Models\CommunityGroup;
use App\Models\Activity;
use App\Models\Category;
use App\Models\User;

class MemberContributionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_a_member_contribution_and_verify_relationships()
    {
        $group = CommunityGroup::create(['group_name' => 'Banjar', 'description' => 'Test']);
        $activity = Activity::create(['activity_name' => 'Test Activity', 'start_date' => now(), 'end_date' => now()->addDays(1), 'status' => 'planned']);
        $fund = Fund::create(['group_id' => $group->group_id, 'activity_id' => $activity->activity_id, 'name' => 'Fund', 'current_balance' => 0, 'description' => 'Test']);
        $member = Member::create(['group_id' => $group->group_id, 'member_name' => 'Wayan', 'status' => 'active']);
        $categoryParent = Category::create(['category_name' => 'Parent', 'type' => 'income']);
        $category = Category::create(['category_name' => 'Child', 'type' => 'income', 'parent_id' => $categoryParent->category_id]);
        $user = User::create(['name' => 'User', 'email' => 'test@test.com', 'password' => 'pass', 'role' => 'treasurer']);

        $transaction = Transaction::create([
            'category_id' => $category->category_id,
            'fund_id' => $fund->fund_id,
            'user_id' => $user->user_id,
            'type' => 'income',
            'amount' => 50000,
            'date' => now(),
            'description' => 'Urunan',
            'validation_status' => 'validated'
        ]);

        $contribution = MemberContribution::create([
            'member_id' => $member->member_id,
            'transaction_id' => $transaction->transaction_id,
            'fund_id' => $fund->fund_id,
            'payment_status' => 'paid',
        ]);

        $this->assertDatabaseHas('member_contributions', [
            'member_id' => $member->member_id,
            'payment_status' => 'paid',
        ]);

        $this->assertEquals('Wayan', $contribution->member->member_name);
        $this->assertEquals(50000, $contribution->transaction->amount);
        $this->assertEquals('Fund', $contribution->fund->name);
    }
}
