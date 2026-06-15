<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_a_parent_category()
    {
        $category = Category::create([
            'category_name' => 'Main Category',
            'type' => 'income',
        ]);

        $this->assertDatabaseHas('categories', [
            'category_name' => 'Main Category',
            'parent_id' => null,
        ]);
    }

    public function test_it_can_create_a_child_category_and_verify_relationships()
    {
        $parent = Category::create([
            'category_name' => 'Parent',
            'type' => 'income',
        ]);

        $child = Category::create([
            'category_name' => 'Child',
            'type' => 'income',
            'parent_id' => $parent->category_id,
        ]);

        // Assert relationships
        $this->assertEquals($parent->category_id, $child->parent->category_id);
        $this->assertTrue($parent->children->contains($child));
    }
}
