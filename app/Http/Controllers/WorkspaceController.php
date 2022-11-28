<?php

namespace App\Http\Controllers;

use App\Http\Requests\Workspace\StoreRequest;
use App\Http\Requests\Workspace\UpdateRequest;
use App\Http\Resources\WorkspaceResource;
use App\Models\Workspace;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    public function show(Request $request, Workspace $workspace)
    {
        $workspaceWithBoards = $workspace->with('boards')->first();
        return WorkspaceResource::make($workspaceWithBoards);
    }

    public function store(StoreRequest $request)
    {
        $request['slug'] = \Illuminate\Support\Str::slug($request['name']);
        Workspace::create($request->toArray());

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
}
