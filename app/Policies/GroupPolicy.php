<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // allow to edit group only to group members
    public function update(User $user, Group $group)
    {
        $groupUsersIds = $group->users->pluck('id')->toArray();

        return in_array($user->id, $groupUsersIds);
    }

    // allow to delete group only to group members
    public function delete(User $user, Group $group)
    {
        $groupUsersIds = $group->users->pluck('id')->toArray();

        return in_array($user->id, $groupUsersIds);
    }

    // allow to invite to group only to group members
    public function invite(User $user, Group $group)
    {
        $groupUsersIds = $group->users->pluck('id')->toArray();

        return in_array($user->id, $groupUsersIds);
    }
}
