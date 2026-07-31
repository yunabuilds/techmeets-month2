@extends('layouts.app')

@section('title', 'イベント編集')

@section('content')
    <h1>イベント編集</h1>

    <form action="{{ route('events.update', $event) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label>イベント名</label><br>
            <input type="text" name="name" value="{{ old('name', $event->name) }}">
            @error('name')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>説明</label><br>
            <textarea name="description">{{ old('description', $event->description) }}</textarea>
            @error('description')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>開催日時</label><br>
            <input type="datetime-local" name="date" value="{{ old('date', $event->date) }}">
            @error('date')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <button type="submit">更新する</button>
    </form>
@endsection