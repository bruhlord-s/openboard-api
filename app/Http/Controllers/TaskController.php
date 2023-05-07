<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Models\Task;
use App\Models\TaskAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function store(StoreRequest $request)
    {
        $task = Task::create($request->toArray());
        
        if ($request->attachments) {
            foreach($request->attachments as $attachment) {
                $path = Storage::disk('public')->put('attachments/' . $task->id, $attachment);
    
                TaskAttachment::create([
                    'task_id' => $task->id,
                    'file' => Storage::url($path),
                ]);
            }
        }
        

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
