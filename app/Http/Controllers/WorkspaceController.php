<?php

namespace App\Http\Controllers;

use App\Http\Requests\Workspace\StoreRequest;
use App\Http\Requests\Workspace\UpdateRequest;
use App\Http\Resources\BoardResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\WorkspaceResource;
use App\Models\Board;
use App\Models\Group;
use App\Models\Workspace;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    public function show(Request $request, Workspace $workspace)
    {
        $workspaceWithBoards = Workspace::whereId($workspace->id)->with(['boards.tasks.user', 'group.users'])->first();
        return WorkspaceResource::make($workspaceWithBoards);
    }

    public function store(StoreRequest $request)
    {
        // TODO: move this to policy
        $group = Group::whereId($request['group_id'])->first();
        $groupUsersIds = $group->users->pluck('id')->toArray();

        if (!in_array($request->user()->id, $groupUsersIds)) {
            return response([], 403);
        }

        $request['slug'] = \Illuminate\Support\Str::slug($request['name']);
        $workspace = Workspace::create($request->toArray());

        $workspace->boards()->createMany([
            [
                'name' => 'Бэклог',
                'slug' => 'backlog',
                'color' => '#797979',
                'workspace_id' => $workspace->id
            ],
            [
                'name' => 'В работе',
                'slug' => 'in-progress',
                'color' => '#005eff',
                'workspace_id' => $workspace->id
            ],
            [
                'name' => 'Готово',
                'slug' => 'complete',
                'color' => '#00bc24',
                'workspace_id' => $workspace->id
            ],
        ]);

        return response([], 201);
    }

    public function update(UpdateRequest $request, Workspace $workspace)
    {
        $request['slug'] = \Illuminate\Support\Str::slug($request['name']);
        $workspace->update($request->toArray());

        return response([], 204);
    }

    public function delete(Workspace $workspace)
    {
        $workspace->delete();

        return response([]);
    }

    public function boards(Request $request, Workspace $workspace)
    {
        return BoardResource::collection($workspace->boards()->get());
    }

    public function members(Request $request, Workspace $workspace)
    {
        return UserResource::collection($workspace->group->users()->get());
    }
}
