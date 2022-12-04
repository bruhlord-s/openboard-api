<?php

namespace App\Http\Controllers;

use App\Http\Requests\Board\StoreRequest;
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
        $request['slug'] = \Illuminate\Support\Str::slug($request['name']);
        Board::create($request->toArray());

        return response([]);
    }
}
