@extends('layouts.app')

@section('title', '投稿編集')

@section('content')
    <h1>投稿編集</h1>

    <form action="{{ route('posts.update', $post) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label>タイトル</label><br>
            <input type="text" name="title" value="{{ old('title', $post->title) }}">
            @error('title')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>カテゴリー</label><br>
            <input type="text" name="category" value="{{ old('category', $post->category) }}">
            @error('category')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>内容</label><br>
            <textarea name="content">{{ old('content', $post->content) }}</textarea>
            @error('content')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <button type="submit">更新する</button>
    </form>
@endsection