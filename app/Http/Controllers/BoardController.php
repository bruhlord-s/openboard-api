<?php

namespace App\Http\Controllers;

use App\Http\Resources\BoardResource;
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
}
