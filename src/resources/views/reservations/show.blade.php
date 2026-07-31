@extends('layouts.app')

@section('title', '予約詳細')

@section('content')
    <h1>予約詳細</h1>

    <p>イベント：{{ $reservation->event->name }}</p>
    <p>お名前：{{ $reservation->name }}様</p>
    <p>メールアドレス：{{ $reservation->email }}</p>
    <p>人数：{{ $reservation->number_of_people }}人</p>

    <form action="{{ route('reservations.destroy', $reservation) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit">予約をキャンセルする</button>
    </form>

    <a href="{{ route('reservations.index') }}">一覧に戻る</a>
@endsection