@extends('layouts.app')

@section('title', '商品一覧')

@section('content')
    <h1>商品一覧</h1>

    <a href="{{ route('products.create') }}">新規商品登録</a>

    @forelse ($products as $product)
        <article>
            <h2>
                <a href="{{ route('products.show', $product) }}">
                    {{ $product->name }}
                </a>
            </h2>
            <p>価格：{{ $product->price }}円</p>
            <p>カテゴリー：{{ $product->category }}</p>
        </article>
    @empty
        <p>商品がありません</p>
    @endforelse

    {{ $products->links() }}
@endsection