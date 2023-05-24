<?php

namespace Tests\Feature;

use App\Models\Board;
use App\Models\Group;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BoardTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCantCreateABoard()
    {
        $group = Group::factory()->create();
        $workspace = Workspace::factory()->create([
            'group_id' => $group->id,
        ]);

        $request = $this->postJson('/api/board', [
            'workspace_id' => $workspace->id,
            'name' => 'Board',
            'color' => '#000',
        ]);

        $request->assertStatus(401);
    }

    public function testUserCanCreateABoard()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        $group->users()->attach($user);

        $workspace = Workspace::factory()->create([
            'group_id' => $group->id,
        ]);

        $request = $this->actingAs($user, 'api')->postJson('/api/board', [
            'workspace_id' => $workspace->id,
            'name' => 'Board',
            'color' => '#000',
        ]);

        $request->assertOk();

        $this->assertDatabaseHas('boards', [
            'name' => 'Board'
        ]);
    }

    public function testNonMemberCantCreateABoard()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $workspace = Workspace::factory()->create([
            'group_id' => $group->id,
        ]);

        $request = $this->actingAs($user, 'api')->postJson('/api/board', [
            'workspace_id' => $workspace->id,
            'name' => 'Board',
            'color' => '#000',
        ]);

        $request->assertForbidden();
    }

    public function testMemberCanUpdateABoard()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        $group->users()->attach($user);

        $workspace = Workspace::factory()->create([
            'group_id' => $group->id,
        ]);

        $board = Board::factory()->create([
            'workspace_id' => $workspace->id
        ]);

        $request = $this->actingAs($user, 'api')->putJson('/api/board/' . $board->id, [
            'workspace_id' => $workspace->id,
            'name' => 'Board2',
            'color' => '#000',
        ]);

        $request->assertStatus(204);

        $this->assertDatabaseHas('boards', [
            'name' => 'Board2'
        ]);
    }

    public function testNonMemberCantUpdateABoard()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $workspace = Workspace::factory()->create([
            'group_id' => $group->id,
        ]);

        $board = Board::factory()->create([
            'workspace_id' => $workspace->id
        ]);

        $request = $this->actingAs($user, 'api')->putJson('/api/board/' . $board->id, [
            'workspace_id' => $workspace->id,
            'name' => 'Board2',
            'color' => '#000',
        ]);

        $request->assertForbidden();
    }

    public function testMemberCanDeleteABoard()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        $group->users()->attach($user);

        $workspace = Workspace::factory()->create([
            'group_id' => $group->id,
        ]);

        $board = Board::factory()->create([
            'workspace_id' => $workspace->id
        ]);

        $request = $this->actingAs($user, 'api')->deleteJson('/api/board/' . $board->id);

        $request->assertOk();

        $this->assertDatabaseMissing('boards', [
            'id' => $workspace->id
        ]);
    }

    public function testNonMemberCantDeleteABoard()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $workspace = Workspace::factory()->create([
            'group_id' => $group->id,
        ]);

        $board = Board::factory()->create([
            'workspace_id' => $workspace->id
        ]);

        $request = $this->actingAs($user, 'api')->deleteJson('/api/board/' . $board->id);

        $request->assertForbidden();
    }
}
