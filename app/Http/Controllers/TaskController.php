<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(StoreRequest $request)
    {
        Task::create($request->toArray());

        response([], 201);
    }

    public function update(UpdateRequest $request, Task $task)
    {
        $task->update($request->toArray());

        response([], 204);
    }

    public function delete(Request $request, Task $task)
    {
        $task->delete();

        response([], 204);
    }
}
