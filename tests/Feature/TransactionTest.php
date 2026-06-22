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
            'username' => 'Test User',
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
            'username' => 'Test User',
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
            'username' => 'Test User',
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

    public function test_validated_transaction_cannot_be_reverted()
    {
        $parentCategory = Category::create(['category_name' => 'Header', 'type' => 'income']);
        $childCategory = Category::create(['category_name' => 'Child', 'type' => 'income', 'parent_id' => $parentCategory->category_id]);
        $fund = $this->createFund(100);
        $user = User::create(['username' => 'Test', 'email' => 'test2@example.com', 'password' => 'sec', 'role' => 'treasurer']);

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

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Une transaction validée est scellée et inaltérable. Une contre-passation est requise.');

        $transaction->update(['validation_status' => 'rejected']);
    }

    public function test_validated_transaction_amount_cannot_be_updated()
    {
        $parentCategory = Category::create(['category_name' => 'Header', 'type' => 'income']);
        $childCategory = Category::create(['category_name' => 'Child', 'type' => 'income', 'parent_id' => $parentCategory->category_id]);
        $fund = $this->createFund(100);
        $user = User::create(['username' => 'Test', 'email' => 'test3@example.com', 'password' => 'sec', 'role' => 'treasurer']);

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

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Une transaction validée est scellée et inaltérable. Une contre-passation est requise.');

        $transaction->update(['amount' => 200]);
    }

    public function test_validated_transaction_cannot_be_deleted()
    {
        $parentCategory = Category::create(['category_name' => 'Header', 'type' => 'income']);
        $childCategory = Category::create(['category_name' => 'Child', 'type' => 'income', 'parent_id' => $parentCategory->category_id]);
        $fund = $this->createFund(100);
        $user = User::create(['username' => 'Test', 'email' => 'test4@example.com', 'password' => 'sec', 'role' => 'treasurer']);

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

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Une transaction validée ne peut être supprimée. Une contre-passation est requise.');

        $transaction->delete();
    }
    public function test_user_can_view_transaction_create_form()
    {
        $user = User::create(['username' => 'Test', 'email' => 'testform@example.com', 'password' => 'sec', 'role' => 'treasurer']);
        $response = $this->actingAs($user)->get('/transactions/create');
        $response->assertStatus(200);
        $response->assertViewIs('transactions.create');
    }

    public function test_user_can_store_a_pending_transaction()
    {
        $user = User::create(['username' => 'Test', 'email' => 'teststore@example.com', 'password' => 'sec', 'role' => 'treasurer']);
        
        $parentCategory = Category::create(['category_name' => 'Header', 'type' => 'income']);
        $childCategory = Category::create(['category_name' => 'Child', 'type' => 'income', 'parent_id' => $parentCategory->category_id]);
        $fund = $this->createFund(100);

        $response = $this->actingAs($user)->post('/transactions', [
            'amount' => 500,
            'category_id' => $childCategory->category_id,
            'fund_id' => $fund->fund_id,
            'type' => 'income',
            'date' => now()->format('Y-m-d'),
            'description' => 'Test Transaction Submit',
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('transactions', [
            'amount' => 500,
            'description' => 'Test Transaction Submit',
            'validation_status' => 'pending'
        ]);
    }

    public function test_treasurer_cannot_view_or_approve_pending_transactions()
    {
        $treasurer = User::create(['username' => 'Treasurer', 'email' => 't1@example.com', 'password' => 'sec', 'role' => 'treasurer']);
        $response = $this->actingAs($treasurer)->get(route('transactions.pending'));
        $response->assertStatus(403);
    }

    public function test_kelian_can_approve_transaction_and_update_fund()
    {
        $kelian = User::create(['username' => 'Kelian', 'email' => 'k1@example.com', 'password' => 'sec', 'role' => 'kelian']);
        $treasurer = User::create(['username' => 'Treasurer2', 'email' => 't2@example.com', 'password' => 'sec', 'role' => 'treasurer']);
        
        $parentCategory = Category::create(['category_name' => 'Header', 'type' => 'income']);
        $childCategory = Category::create(['category_name' => 'Child', 'type' => 'income', 'parent_id' => $parentCategory->category_id]);
        $fund = $this->createFund(100);
        
        $transaction = Transaction::create([
            'category_id' => $childCategory->category_id,
            'fund_id' => $fund->fund_id,
            'user_id' => $treasurer->user_id,
            'type' => 'income',
            'amount' => 50,
            'date' => now(),
            'description' => 'Pending Income',
            'validation_status' => 'pending',
        ]);

        $response = $this->actingAs($kelian)->patch(route('transactions.approve', $transaction));
        
        $response->assertRedirect();
        
        $this->assertDatabaseHas('transactions', [
            'transaction_id' => $transaction->transaction_id,
            'validation_status' => 'validated',
            'validated_by' => $kelian->user_id,
        ]);

        $this->assertEquals(150, $fund->fresh()->current_balance);
    }
}
