<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_executes_successfully()
    {
        $this->artisan('db:seed')->assertSuccessful();

        // 1. Verify that fundamental roles have been generated (Kelian, Treasurer)
        $this->assertTrue(User::where('role', 'kelian')->exists(), 'A user with the role of Kelian should exist.');
        $this->assertTrue(User::where('role', 'treasurer')->exists(), 'A user with the role of Treasurer should exist.');

        // 2. Verify structural integrity of MYOB hierarchical categories
        $this->assertTrue(Category::whereNull('parent_id')->exists(), 'At least one Header Account (parent_id = NULL) should exist.');
        $this->assertTrue(Category::whereNotNull('parent_id')->exists(), 'At least one Sub-category (parent_id != NULL) should exist.');
    }
}
