@extends('layouts.app')

@section('title', '新規タスク')

@section('content')
    <h1>新規タスク</h1>

    <form action="{{ route('tasks.store') }}" method="POST">
        @csrf

        <div>
            <label>タイトル</label><br>
            <input type="text" name="title" value="{{ old('title') }}">
            @error('title')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>詳細</label><br>
            <textarea name="description">{{ old('description') }}</textarea>
            @error('description')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>期限</label><br>
            <input type="date" name="due_date" value="{{ old('due_date') }}">
            @error('due_date')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>優先度</label><br>
            <input type="text" name="priority" value="{{ old('priority') }}">
            @error('priority')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <button type="submit">タスクを作成する</button>
    </form>
@endsection