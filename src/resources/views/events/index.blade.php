@extends('layouts.app')

@section('title', 'イベント一覧')

@section('content')
    <h1>イベント一覧</h1>

    <a href="{{ route('events.create') }}">新規イベント登録</a>

    @forelse ($events as $event)
        <article>
            <h2>
                <a href="{{ route('events.show', $event) }}">
                    {{ $event->name }}
                </a>
            </h2>
            <p>開催日時：{{ $event->date }}</p>
        </article>
    @empty
        <p>イベントがありません</p>
    @endforelse

    {{ $events->links() }}
@endsection