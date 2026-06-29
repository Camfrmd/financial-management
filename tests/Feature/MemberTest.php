<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Member;
use App\Models\CommunityGroup;

class MemberTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_a_member_and_link_to_community_group()
    {
        $group = CommunityGroup::create([
            'group_name' => 'Banjar Utara',
            'description' => 'Northern community group',
        ]);

        $member = Member::create([
            'group_id' => $group->group_id,
            'member_name' => 'Wayan Budi',
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('members', [
            'member_name' => 'Wayan Budi',
            'group_id' => $group->group_id,
        ]);

        $this->assertEquals('Banjar Utara', $member->group->group_name);
    }

    public function test_deleting_community_group_cascades_to_members()
    {
        $group = CommunityGroup::create([
            'group_name' => 'Banjar Selatan',
            'description' => 'Southern community group',
        ]);

        $member = Member::create([
            'group_id' => $group->group_id,
            'member_name' => 'Made Budi',
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('members', ['member_id' => $member->member_id]);

        $group->delete();

        $this->assertDatabaseMissing('members', ['member_id' => $member->member_id]);
    }
}
