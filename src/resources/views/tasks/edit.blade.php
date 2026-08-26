@extends('layouts.app')

@section('title', 'タスク編集')

@section('content')
    <h1>タスク編集</h1>

    <form action="{{ route('tasks.update', $task) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label>タイトル</label><br>
            <input type="text" name="title" value="{{ old('title', $task->title) }}">
            @error('title')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>詳細</label><br>
            <textarea name="description">{{ old('description', $task->description) }}</textarea>
            @error('description')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>期限</label><br>
            <input type="date" name="due_date" value="{{ old('due_date', $task->due_date) }}">
            @error('due_date')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>優先度</label><br>
            <input type="text" name="priority" value="{{ old('priority', $task->priority) }}">
            @error('priority')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <button type="submit">更新する</button>
    </form>
@endsection