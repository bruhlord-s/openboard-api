<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCantCreateAWorkspace()
    {
        $request = $this->postJson('/api/workspace', [
            'name' => 'Workspace',
        ]);

        $request->assertStatus(401);
    }

    public function testUserCanCreateAWorkspace()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        $group->users()->attach($user);

        $request = $this->actingAs($user, 'api')->postJson('/api/workspace', [
            'name' => 'Workspace',
            'group_id' => $group->id
        ]);

        $request->assertCreated();

        $this->assertDatabaseHas('workspaces', [
            'name' => 'Workspace'
        ]);
    }

    public function testNonMemberCantCreateAWorkspaceInAGroup()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $request = $this->actingAs($user, 'api')->postJson('/api/workspace', [
            'name' => 'Workspace',
            'group_id' => $group->id
        ]);

        $request->assertForbidden();
    }

    public function testMemberCanUpdateAWorkspace()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        $group->users()->attach($user);

        $workspace = Workspace::factory()->create(['group_id' => $group->id]);

        $request = $this->actingAs($user, 'api')->putJson('/api/workspace/' . $workspace->id, [
            'name' => 'Workspace2',
        ]);

        $request->assertStatus(204);

        $this->assertDatabaseHas('workspaces', [
            'name' => 'Workspace2'
        ]);
    }

    public function testNonMemberCantUpdateAWorkspace()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $workspace = Workspace::factory()->create(['group_id' => $group->id]);

        $request = $this->actingAs($user, 'api')->putJson('/api/workspace/' . $workspace->id, [
            'name' => 'Workspace2',
        ]);

        $request->assertForbidden();
    }

    public function testMemberCanDeleteAWorkspace()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        $group->users()->attach($user);

        $workspace = Workspace::factory()->create(['group_id' => $group->id]);

        $request = $this->actingAs($user, 'api')->deleteJson('/api/workspace/' . $workspace->id);

        $request->assertOk();

        $this->assertDatabaseMissing('workspaces', [
            'id' => $workspace->id
        ]);
    }

    public function testNonMemberCantDeleteAWorkspace()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $workspace = Workspace::factory()->create(['group_id' => $group->id]);

        $request = $this->actingAs($user, 'api')->deleteJson('/api/workspace/' . $workspace->id);

        $request->assertForbidden();
    }
}
