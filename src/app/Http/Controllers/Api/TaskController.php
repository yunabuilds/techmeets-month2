<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Request;

class TaskController extends Controller
{
   public function index()
{
    $tasks = Task::all();
    return TaskResource::collection($tasks);
}

public function store(Request $request)
   {
       $validated = $request->validate([
           'title'        => 'required|string|max:255',
           'description'  => 'nullable|string',
           'due_date'     => 'required|date',
           'priority'     => 'required|string',
           'is_completed' => 'boolean',
           'user_id'      => 'required|exists:users,id',
       ]);

       $task = Task::create($validated);

       return new TaskResource($task);
   }
}
