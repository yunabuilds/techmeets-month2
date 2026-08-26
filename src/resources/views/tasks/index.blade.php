@extends('layouts.app')

@section('title', 'タスク一覧')

@section('content')
    <h1>タスク一覧</h1>

    <a href="{{ route('tasks.create') }}">新規タスク</a>

    @forelse ($tasks as $task)
        <article>
            <h2>
                <a href="{{ route('tasks.show', $task) }}">
                    {{ $task->title }}
                </a>
            </h2>
            <p>{{ Str::limit($task->description, 100) }}</p>
            <small>期限: {{ $task->due_date }}</small>
            <small>優先度: {{ $task->priority }}</small>
            <small>{{ $task->is_completed ? '完了' : '未完了' }}</small>
        </article>
    @empty
        <p>タスクがありません</p>
    @endforelse

    {{ $tasks->links() }}
@endsection