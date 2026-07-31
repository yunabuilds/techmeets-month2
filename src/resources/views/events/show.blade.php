@extends('layouts.app')

@section('title', $event->name)

@section('content')
    <h1>{{ $event->name }}</h1>

    <p>開催日時：{{ $event->date }}</p>

    <div>
        {{ $event->description }}
    </div>

    <a href="{{ route('events.edit', $event) }}">編集</a>

    <form action="{{ route('events.destroy', $event) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit">削除</button>
    </form>

    <a href="{{ route('reservations.create') }}">このイベントに予約する</a>

    <a href="{{ route('events.index') }}">一覧に戻る</a>
@endsection