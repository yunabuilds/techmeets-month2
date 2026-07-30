@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <h1>{{ $product->name }}</h1>

    <p>価格：{{ $product->price }}円</p>
    <p>在庫数：{{ $product->stock }}</p>
    <p>カテゴリー：{{ $product->category }}</p>

    <div>
        {{ $product->description }}
    </div>

    <a href="{{ route('products.edit', $product) }}">編集</a>

    <form action="{{ route('products.destroy', $product) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit">削除</button>
    </form>

    <a href="{{ route('products.index') }}">一覧に戻る</a>
@endsection