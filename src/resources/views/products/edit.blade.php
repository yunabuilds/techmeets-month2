@extends('layouts.app')

@section('title', '商品編集')

@section('content')
    <h1>商品編集</h1>

    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label>商品名</label><br>
            <input type="text" name="name" value="{{ old('name', $product->name) }}">
            @error('name')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>価格</label><br>
            <input type="number" name="price" value="{{ old('price', $product->price) }}">
            @error('price')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>説明</label><br>
            <textarea name="description">{{ old('description', $product->description) }}</textarea>
            @error('description')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>在庫数</label><br>
            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}">
            @error('stock')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>カテゴリー</label><br>
            <input type="text" name="category" value="{{ old('category', $product->category) }}">
            @error('category')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <button type="submit">更新する</button>
    </form>
@endsection