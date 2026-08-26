<?php

namespace App\Services;

use App\Repositories\TaskRepository;

class TaskService
{
    public function __construct(
        private TaskRepository $taskRepository
    ) {}

    public function createTask(array $data)
    {
        return $this->taskRepository->create($data);
    }

    public function updateTask(string $id, array $data)
    {
        $task = $this->taskRepository->findById($id);
        return $this->taskRepository->update($task, $data);
    }

    public function deleteTask(string $id)
    {
        $task = $this->taskRepository->findById($id);
        return $this->taskRepository->delete($task);
    }

    public function markAsCompleted(string $id)
{
    $task = $this->taskRepository->findById($id);
    return $this->taskRepository->update($task, ['is_completed' => true]);
}
}