<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupTest extends TestCase
{   
    use RefreshDatabase;

    public function testGuestCantCreateAGroup()
    {
        $request = $this->postJson('/api/group', [
            'name' => 'Group',
        ]);

        $request->assertStatus(401);
    }

    public function testUserCanCreateAGroup()
    {
        $user = User::factory()->create();

        $request = $this->actingAs($user, 'api')->postJson('/api/group', [
            'name' => 'Group',
        ]);

        $request->assertCreated();

        $this->assertDatabaseHas('groups', [
            'name' => 'Group'
        ]);
    }

    public function testNonMemberCantUpdateGroup()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $request = $this->actingAs($user, 'api')->putJson('/api/group/' . $group->id, [
            'name' => 'Group',
        ]);

        $request->assertForbidden();
    }

    public function testMemberCanUpdateGroup()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        $group->users()->attach($user);

        $request = $this->actingAs($user, 'api')->putJson('/api/group/' . $group->id, [
            'name' => 'Edited Group',
        ]);

        $request->assertStatus(204);

        $this->assertDatabaseHas('groups', [
            'name' => 'Edited Group',
        ]);
    }

    public function testNonMemberCantDeleteGroup()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $request = $this->actingAs($user, 'api')->deleteJson('/api/group/' . $group->id);

        $request->assertForbidden();
    }

    public function testMemberCanDeleteGroup()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        $group->users()->attach($user);

        $request = $this->actingAs($user, 'api')->deleteJson('/api/group/' . $group->id);

        $request->assertOk();

        $this->assertDatabaseCount('groups', 0);
    }

    public function testNonMemberCantInviteToGroup()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $request = $this->actingAs($user, 'api')->postJson('/api/group/invite/' . $group->id, [
            'user' => 'test@test.com'
        ]);

        $request->assertForbidden();
    }

    public function testCantInviteNonExistingUser()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        $group->users()->attach($user);

        $request = $this->actingAs($user, 'api')->postJson('/api/group/invite/' . $group->id, [
            'user' => 'test@test.com'
        ]);

        $request->assertStatus(422);
    }

    public function testMemberCanInviteToGroup()
    {
        $invitedUser = User::create([
            'email' => 'test@test.com',
            'name' => 'Test',
            'password' => 'password'
        ]);

        $user = User::factory()->create();
        $group = Group::factory()->create();
        $group->users()->attach($user);

        $request = $this->actingAs($user, 'api')->postJson('/api/group/invite/' . $group->id, [
            'email' => 'test@test.com'
        ]);

        $request->assertOk();

        $this->assertDatabaseHas('group_user', [
            'group_id' => $group->id,
            'user_id' => $invitedUser->id,
        ]);
    }
}
