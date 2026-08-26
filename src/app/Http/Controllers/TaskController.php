<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Repositories\TaskRepository;

class TaskController extends Controller
{
    public function __construct(
        private TaskService $taskService,
        private TaskRepository $taskRepository
    ) {}

    public function index()
    {
        $tasks = $this->taskRepository->getAll();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:200',
            'description' => 'required',
            'due_date' => 'required|date',
            'priority' => 'required',
        ]);

        $validated['user_id'] = auth()->id();

        $task = $this->taskService->createTask($validated);

        return redirect()->route('tasks.show', $task)->with('success', 'タスクを作成しました');
    }

    public function show(string $id)
    {
        $task = $this->taskRepository->findById($id);
        return view('tasks.show', compact('task'));
    }

    public function edit(string $id)
    {
        $task = $this->taskRepository->findById($id);
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, string $id)
    {
        $task = $this->taskRepository->findById($id);
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|max:200',
            'description' => 'required',
            'due_date' => 'required|date',
            'priority' => 'required',
        ]);

        $task = $this->taskService->updateTask($id, $validated);

        return redirect()->route('tasks.show', $task)->with('success', 'タスクを更新しました');
    }

    public function destroy(string $id)
    {
        $task = $this->taskRepository->findById($id);
        $this->authorize('delete', $task);

        $this->taskService->deleteTask($id);

        return redirect()->route('tasks.index')->with('success', 'タスクを削除しました');
    }
}
