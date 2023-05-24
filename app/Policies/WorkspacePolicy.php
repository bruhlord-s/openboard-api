<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkspacePolicy
{
    use HandlesAuthorization;

    // allow to update a workspace only for group member
    public function update(User $user, Workspace $workspace)
    {
        $groupUsersIds = $workspace->group->users->pluck('id')->toArray();

        return in_array($user->id, $groupUsersIds);
    }

    // allow to delete a workspace only for group member
    public function delete(User $user, Workspace $workspace)
    {
        $groupUsersIds = $workspace->group->users->pluck('id')->toArray();

        return in_array($user->id, $groupUsersIds);
    }
}
