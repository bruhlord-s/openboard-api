<?php

namespace App\Http\Controllers;

use App\Http\Requests\Group\InviteToGroupRequest;
use App\Http\Requests\Group\StoreRequest;
use App\Http\Requests\Group\JoinGroupRequest;
use App\Http\Requests\Group\UpdateRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function show(Request $request, Group $group)
    {
        $groupWithUsers = $group->with(['users'])->first();
        return GroupResource::make($groupWithUsers);
    }

    public function store(StoreRequest $request)
    {
        $request['slug'] = \Illuminate\Support\Str::slug($request['name']);
        $group = Group::create($request->toArray());
        $group->users()->attach($request->user()->id);

        return response([], 201);
    }

    public function update(UpdateRequest $request, Group $group)
    {
        $request['slug'] = \Illuminate\Support\Str::slug($request['name']);
        $group->update($request->toArray());

        return response([], 204);
    }

    public function delete(Group $group)
    {
        $group->users()->detach();
        $group->delete();

        return response([]);
    }

    public function join(JoinGroupRequest $request)
    {
        $user = $request->user();
        $group = Group::where('slug', $request['group'])->first();

        $group->users()->attach($user);

        return response([], 201);
    }

    public function invite(InviteToGroupRequest $request, Group $group)
    {
        $user = User::whereEmail($request['email'])->first();
        $group->users()->attach($user);

        return response([]);
    }
}
