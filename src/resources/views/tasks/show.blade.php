@extends('layouts.app')

@section('title', $task->title)

@section('content')
    <h1>{{ $task->title }}</h1>

    <p>優先度：{{ $task->priority }}</p>
    <p>期限：{{ $task->due_date }}</p>
    <p>状態：{{ $task->is_completed ? '完了' : '未完了' }}</p>
    <small>{{ $task->created_at->format('Y年m月d日') }}</small>

    <div>
        {{ $task->description }}
    </div>

    @can('update', $task)
        <a href="{{ route('tasks.edit', $task) }}">編集</a>
    @endcan

    @can('delete', $task)
        <form action="{{ route('tasks.destroy', $task) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">削除</button>
        </form>
    @endcan

    <a href="{{ route('tasks.index') }}">一覧に戻る</a>
@endsection