@extends('layouts.app')

@section('title', '新規イベント登録')

@section('content')
    <h1>新規イベント登録</h1>

    <form action="{{ route('events.store') }}" method="POST">
        @csrf

        <div>
            <label>イベント名</label><br>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>説明</label><br>
            <textarea name="description">{{ old('description') }}</textarea>
            @error('description')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>開催日時</label><br>
            <input type="datetime-local" name="date" value="{{ old('date') }}">
            @error('date')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <button type="submit">登録する</button>
    </form>
@endsection