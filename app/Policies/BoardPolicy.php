<?php

namespace App\Policies;

use App\Models\Board;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BoardPolicy
{
    use HandlesAuthorization;

    // allow to edit board only to group members
    public function update(User $user, Board $board)
    {
        $groupUsersIds = $board->workspace->group->users->pluck('id')->toArray();

        return in_array($user->id, $groupUsersIds);
    }

    // allow to delete board only to group members
    public function delete(User $user, Board $board)
    {
        $groupUsersIds = $board->workspace->group->users->pluck('id')->toArray();

        return in_array($user->id, $groupUsersIds);
    }
}
