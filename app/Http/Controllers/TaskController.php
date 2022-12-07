<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreRequest;
use App\Models\Task;

class TaskController extends Controller
{
    public function store(StoreRequest $request)
    {
        Task::create($request->toArray());

        response([], 201);
    }
}
