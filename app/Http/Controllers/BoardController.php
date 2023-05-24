<?php

namespace App\Http\Controllers;

use App\Http\Requests\Board\StoreRequest;
use App\Http\Requests\Board\UpdateRequest;
use App\Http\Resources\BoardResource;
use App\Models\Board;
use App\Models\Workspace;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * Returns a collection of all boards that belongs to given workspace
     *
     * @param Request $request
     * @param Workspace $workspace
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, Workspace $workspace) {
        return BoardResource::collection($workspace->boards());
    }

    public function store(StoreRequest $request)
    {   
        // TODO: move this to policy
        $workspace = Workspace::whereId($request['workspace_id'])->first();
        $groupUsersIds = $workspace->group->users->pluck('id')->toArray();

        if (!in_array($request->user()->id, $groupUsersIds)) {
            return response([], 403);
        }
        
        $request['slug'] = \Illuminate\Support\Str::slug($request['name']);
        Board::create($request->toArray());

        return response([]);
    }

    public function update(UpdateRequest $request, Board $board)
    {
        $request['slug'] = \Illuminate\Support\Str::slug($request['name']);
        $board->update($request->toArray());

        return response([], 204);
    }

    public function delete(Board $board)
    {
        $board->delete();

        return response([]);
    }
}
