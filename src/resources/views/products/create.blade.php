@extends('layouts.app')

@section('title', '新規商品登録')

@section('content')
    <h1>新規商品登録</h1>

    <form action="{{ route('products.store') }}" method="POST">
        @csrf

        <div>
            <label>商品名</label><br>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>価格</label><br>
            <input type="number" name="price" value="{{ old('price') }}">
            @error('price')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>説明</label><br>
            <textarea name="description">{{ old('description') }}</textarea>
            @error('description')
                <span>{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label>在庫数</label><br>
            <input type="number" name="stock" value="{{ old('stock') }}">
            @error('stock')
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

        <button type="submit">登録する</button>
    </form>
@endsection