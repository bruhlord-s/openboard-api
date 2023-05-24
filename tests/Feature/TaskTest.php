<?php

namespace Tests\Feature;

use App\Models\Board;
use App\Models\Group;
use App\Models\Task;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCantCreateATask()
    {
        $group = Group::factory()->create();
        $workspace = Workspace::factory()->create([
            'group_id' => $group->id,
        ]);
        $board = Board::factory()->create([
            'workspace_id' => $workspace->id,
        ]);

        $request = $this->postJson('/api/task', [
            'board_id' => $board->id,
            'name' => 'Task'
        ]);

        $request->assertUnauthorized();
    }

    public function testUserCanCreateATask()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        $group->users()->attach($user);
        $workspace = Workspace::factory()->create([
            'group_id' => $group->id,
        ]);
        $board = Board::factory()->create([
            'workspace_id' => $workspace->id,
        ]);

        $request = $this->actingAs($user, 'api')->postJson('/api/task', [
            'board_id' => $board->id,
            'name' => 'Task'
        ]);

        $request->assertOk();

        $this->assertDatabaseHas('tasks', [
            'name' => 'Task'
        ]);
    }

    public function testNonMemberCantUpdateATask()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $workspace = Workspace::factory()->create([
            'group_id' => $group->id,
        ]);
        $board = Board::factory()->create([
            'workspace_id' => $workspace->id,
        ]);
        $task = Task::factory()->create([
            'board_id' => $board->id,
        ]);

        $request = $this->actingAs($user, 'api')->putJson('/api/task/' . $task->id, [
            'board_id' => $board->id,
            'name' => 'Task2'
        ]);

        $request->assertForbidden();
    }

    public function testMemberCanUpdateATask()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        $group->users()->attach($user);

        $workspace = Workspace::factory()->create([
            'group_id' => $group->id,
        ]);
        $board = Board::factory()->create([
            'workspace_id' => $workspace->id,
        ]);
        $task = Task::factory()->create([
            'board_id' => $board->id,
        ]);

        $request = $this->actingAs($user, 'api')->putJson('/api/task/' . $task->id, [
            'board_id' => $board->id,
            'name' => 'Task2'
        ]);

        $request->assertOk();

        $this->assertDatabaseHas('tasks', [
            'name' => 'Task2'
        ]);
    }

    public function testNonMemberCantDeleteATask()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $workspace = Workspace::factory()->create([
            'group_id' => $group->id,
        ]);
        $board = Board::factory()->create([
            'workspace_id' => $workspace->id,
        ]);
        $task = Task::factory()->create([
            'board_id' => $board->id,
        ]);

        $request = $this->actingAs($user, 'api')->deleteJson('/api/task/' . $task->id);

        $request->assertForbidden();
    }

    public function testMemberCanDeleteATask()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        $group->users()->attach($user);

        $workspace = Workspace::factory()->create([
            'group_id' => $group->id,
        ]);
        $board = Board::factory()->create([
            'workspace_id' => $workspace->id,
        ]);
        $task = Task::factory()->create([
            'board_id' => $board->id,
        ]);

        $request = $this->actingAs($user, 'api')->deleteJson('/api/task/' . $task->id);

        $request->assertOk();

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id
        ]);
    }
}
