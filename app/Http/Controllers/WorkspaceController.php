<?php

namespace App\Http\Controllers;

use App\Http\Requests\Workspace\StoreRequest;
use App\Http\Requests\Workspace\UpdateRequest;
use App\Http\Resources\BoardResource;
use App\Http\Resources\WorkspaceResource;
use App\Models\Board;
use App\Models\Workspace;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    public function show(Request $request, Workspace $workspace)
    {
        $workspaceWithBoards = Workspace::whereId($workspace->id)->with('boards')->first();
        return WorkspaceResource::make($workspaceWithBoards);
    }

    public function store(StoreRequest $request)
    {
        $request['slug'] = \Illuminate\Support\Str::slug($request['name']);
        $workspace = Workspace::create($request->toArray());

        $workspace->boards()->createMany([
            [
                'name' => 'To Do',
                'slug' => 'to-do',
                'color' => '#797979',
                'workspace_id' => $workspace->id
            ],
            [
                'name' => 'In Progress',
                'slug' => 'in-progress',
                'color' => '#005eff',
                'workspace_id' => $workspace->id
            ],
            [
                'name' => 'Complete',
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

    public function showBoards(Request $request, Workspace $workspace)
    {
        return BoardResource::collection($workspace->boards()->get());
    }
}
