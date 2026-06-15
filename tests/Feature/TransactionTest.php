<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Fund;
use App\Models\User;
use App\Models\CommunityGroup;
use App\Models\Activity;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    private function createFund($balance = 0)
    {
        $group = CommunityGroup::create([
            'group_name' => 'Test Group',
            'description' => 'Test',
        ]);

        $activity = Activity::create([
            'activity_name' => 'Test Activity',
            'start_date' => now(),
            'end_date' => now()->addDays(1),
            'status' => 'planned',
        ]);

        return Fund::create([
            'group_id' => $group->group_id,
            'activity_id' => $activity->activity_id,
            'name' => 'General Fund',
            'current_balance' => $balance,
            'description' => 'Test Fund',
        ]);
    }

    public function test_transaction_cannot_be_linked_to_parent_category()
    {
        $parentCategory = Category::create([
            'category_name' => 'Header Account',
            'type' => 'income',
        ]);

        $fund = $this->createFund(0);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'secret',
            'role' => 'treasurer',
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('A transaction cannot be linked to a parent category (Header Account).');

        Transaction::create([
            'category_id' => $parentCategory->category_id,
            'fund_id' => $fund->fund_id,
            'user_id' => $user->user_id,
            'type' => 'income',
            'amount' => 100,
            'date' => now(),
            'description' => 'Test',
        ]);
    }

    public function test_validated_transaction_updates_fund_balance()
    {
        $parentCategory = Category::create([
            'category_name' => 'Header Account',
            'type' => 'income',
        ]);

        $childCategory = Category::create([
            'category_name' => 'Child Account',
            'type' => 'income',
            'parent_id' => $parentCategory->category_id,
        ]);

        $fund = $this->createFund(0);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'secret',
            'role' => 'treasurer',
        ]);

        $transaction = Transaction::create([
            'category_id' => $childCategory->category_id,
            'fund_id' => $fund->fund_id,
            'user_id' => $user->user_id,
            'type' => 'income',
            'amount' => 100,
            'date' => now(),
            'description' => 'Test',
            'validation_status' => 'pending',
        ]);

        $this->assertEquals(0, $fund->fresh()->current_balance);

        $transaction->update(['validation_status' => 'validated']);

        $this->assertEquals(100, $fund->fresh()->current_balance);
    }

    public function test_rejected_transaction_does_not_update_fund_balance()
    {
        $parentCategory = Category::create([
            'category_name' => 'Header Account',
            'type' => 'income',
        ]);

        $childCategory = Category::create([
            'category_name' => 'Child Account',
            'type' => 'income',
            'parent_id' => $parentCategory->category_id,
        ]);

        $fund = $this->createFund(100); // Initial balance

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'secret',
            'role' => 'treasurer',
        ]);

        $transaction = Transaction::create([
            'category_id' => $childCategory->category_id,
            'fund_id' => $fund->fund_id,
            'user_id' => $user->user_id,
            'type' => 'income',
            'amount' => 50,
            'date' => now(),
            'description' => 'Test',
            'validation_status' => 'pending',
        ]);

        $this->assertEquals(100, $fund->fresh()->current_balance);

        $transaction->update(['validation_status' => 'rejected']);

        $this->assertEquals(100, $fund->fresh()->current_balance);
    }

    public function test_validated_transaction_reverted_to_rejected_reverts_fund_balance()
    {
        $parentCategory = Category::create(['category_name' => 'Header', 'type' => 'income']);
        $childCategory = Category::create(['category_name' => 'Child', 'type' => 'income', 'parent_id' => $parentCategory->category_id]);
        $fund = $this->createFund(100);
        $user = User::create(['name' => 'Test', 'email' => 'test2@example.com', 'password' => 'sec', 'role' => 'treasurer']);

        $transaction = Transaction::create([
            'category_id' => $childCategory->category_id,
            'fund_id' => $fund->fund_id,
            'user_id' => $user->user_id,
            'type' => 'income',
            'amount' => 50,
            'date' => now(),
            'description' => 'Test',
            'validation_status' => 'validated',
        ]);

        $this->assertEquals(150, $fund->fresh()->current_balance);

        $transaction->update(['validation_status' => 'rejected']);

        $this->assertEquals(100, $fund->fresh()->current_balance);
    }

    public function test_validated_transaction_amount_update_syncs_fund_balance()
    {
        $parentCategory = Category::create(['category_name' => 'Header', 'type' => 'income']);
        $childCategory = Category::create(['category_name' => 'Child', 'type' => 'income', 'parent_id' => $parentCategory->category_id]);
        $fund = $this->createFund(100);
        $user = User::create(['name' => 'Test', 'email' => 'test3@example.com', 'password' => 'sec', 'role' => 'treasurer']);

        $transaction = Transaction::create([
            'category_id' => $childCategory->category_id,
            'fund_id' => $fund->fund_id,
            'user_id' => $user->user_id,
            'type' => 'income',
            'amount' => 50,
            'date' => now(),
            'description' => 'Test',
            'validation_status' => 'validated',
        ]);

        $transaction->update(['amount' => 200]);

        $this->assertEquals(300, $fund->fresh()->current_balance);
    }

    public function test_validated_transaction_deleted_reverts_fund_balance()
    {
        $parentCategory = Category::create(['category_name' => 'Header', 'type' => 'income']);
        $childCategory = Category::create(['category_name' => 'Child', 'type' => 'income', 'parent_id' => $parentCategory->category_id]);
        $fund = $this->createFund(100);
        $user = User::create(['name' => 'Test', 'email' => 'test4@example.com', 'password' => 'sec', 'role' => 'treasurer']);

        $transaction = Transaction::create([
            'category_id' => $childCategory->category_id,
            'fund_id' => $fund->fund_id,
            'user_id' => $user->user_id,
            'type' => 'expense',
            'amount' => 40,
            'date' => now(),
            'description' => 'Test',
            'validation_status' => 'validated',
        ]);

        $this->assertEquals(60, $fund->fresh()->current_balance);

        $transaction->delete();

        $this->assertEquals(100, $fund->fresh()->current_balance);
    }
}
