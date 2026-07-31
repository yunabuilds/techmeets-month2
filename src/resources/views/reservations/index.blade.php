@extends('layouts.app')

@section('title', '予約一覧')

@section('content')
    <h1>予約一覧</h1>

    <a href="{{ route('reservations.create') }}">新規予約</a>

    @forelse ($reservations as $reservation)
        <article>
            <h2>
                <a href="{{ route('reservations.show', $reservation) }}">
                    {{ $reservation->name }}様
                </a>
            </h2>
            <p>イベント：{{ $reservation->event->name }}</p>
            <p>人数：{{ $reservation->number_of_people }}人</p>
        </article>
    @empty
        <p>予約がありません</p>
    @endforelse

    {{ $reservations->links() }}
@endsection