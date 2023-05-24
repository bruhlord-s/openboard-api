<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    // allow to edit task only to group members
    public function update(User $user, Task $task)
    {
        $groupUsersIds = $task->board->workspace->group->users->pluck('id')->toArray();

        return in_array($user->id, $groupUsersIds);
    }

    // allow to delete task only to group members
    public function delete(User $user, Task $task)
    {
        $groupUsersIds = $task->board->workspace->group->users->pluck('id')->toArray();

        return in_array($user->id, $groupUsersIds);
    }
}
