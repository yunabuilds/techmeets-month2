<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Repositories\TaskRepository;
use App\Services\TaskService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    private $repository;
    private TaskService $service;

    protected function setUp(): void
    {
        parent::setUp();
        // 本物のRepositoryの代わりに、偽物（モック）を用意する
        $this->repository = Mockery::mock(TaskRepository::class);
        $this->service = new TaskService($this->repository);
    }

    // 正常系：作成のデータがRepositoryに渡される
    public function test_create_task_passes_data_to_repository()
    {
        $data = ['title' => 'Test Task'];
        $task = new Task();
        $this->repository->shouldReceive('create')->once()->with($data)->andReturn($task);

        $result = $this->service->createTask($data);

        $this->assertSame($task, $result);
    }

    // 正常系：更新は「探す → 更新する」の順に呼ばれる
    public function test_update_task_finds_then_updates()
    {
        $task = new Task();
        $data = ['title' => 'Updated'];
        $this->repository->shouldReceive('findById')->once()->with('1')->andReturn($task);
        $this->repository->shouldReceive('update')->once()->with($task, $data)->andReturn($task);

        $result = $this->service->updateTask('1', $data);

        $this->assertSame($task, $result);
    }

    // 正常系：削除は「探す → 削除する」の順に呼ばれる
    public function test_delete_task_finds_then_deletes()
    {
        $task = new Task();
        $this->repository->shouldReceive('findById')->once()->with('1')->andReturn($task);
        $this->repository->shouldReceive('delete')->once()->with($task)->andReturn(true);

        $result = $this->service->deleteTask('1');

        $this->assertTrue($result);
    }

    // 正常系：完了にすると is_completed が true で更新される
    public function test_mark_as_completed_sets_is_completed_true()
    {
        $task = new Task();
        $this->repository->shouldReceive('findById')->once()->with('1')->andReturn($task);
        $this->repository->shouldReceive('update')->once()->with($task, ['is_completed' => true])->andReturn($task);

        $result = $this->service->markAsCompleted('1');

        $this->assertSame($task, $result);
    }

    // 異常系：存在しないタスクを更新しようとするとエラーになり、更新は呼ばれない
    public function test_update_task_throws_when_task_not_found()
    {
        $this->repository->shouldReceive('findById')->once()->with('999')->andThrow(new ModelNotFoundException());
        $this->repository->shouldNotReceive('update');

        $this->expectException(ModelNotFoundException::class);

        $this->service->updateTask('999', ['title' => 'x']);
    }
}