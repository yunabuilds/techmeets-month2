@extends('layouts.app')

@section('title', '新規投稿')

@section('content')
    <h1>新規投稿</h1>

    <form action="{{ route('posts.store') }}" method="POST">
        @csrf

        <div>
            <label>タイトル</label><br>
            <input type="text" name="title" value="{{ old('title') }}">
            @error('title')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>カテゴリー</label><br>
            <input type="text" name="category" value="{{ old('category') }}">
            @error('category')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>内容</label><br>
            <textarea name="content">{{ old('content') }}</textarea>
            @error('content')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <button type="submit">投稿する</button>
    </form>
@endsection
