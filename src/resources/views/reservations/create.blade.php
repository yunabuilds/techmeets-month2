@extends('layouts.app')

@section('title', '新規予約')

@section('content')
    <h1>新規予約</h1>

    <form action="{{ route('reservations.store') }}" method="POST">
        @csrf

        <div>
            <label>イベント</label><br>
            <select name="event_id">
                @foreach ($events as $event)
                    <option value="{{ $event->id }}">{{ $event->name }}</option>
                @endforeach
            </select>
            @error('event_id')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>お名前</label><br>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>メールアドレス</label><br>
            <input type="email" name="email" value="{{ old('email') }}">
            @error('email')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>人数</label><br>
            <input type="number" name="number_of_people" value="{{ old('number_of_people') }}">
            @error('number_of_people')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <button type="submit">予約する</button>
    </form>
@endsection