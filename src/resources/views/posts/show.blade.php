@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <h1>{{ $post->title }}</h1>

    <p>カテゴリー：{{ $post->category }}</p>
    <small>{{ $post->created_at->format('Y年m月d日') }}</small>

    <div>
        {{ $post->content }}
    </div>

    <a href="{{ route('posts.edit', $post) }}">編集</a>

    <form action="{{ route('posts.destroy', $post) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit">削除</button>
    </form>

    <a href="{{ route('posts.index') }}">一覧に戻る</a>
@endsection