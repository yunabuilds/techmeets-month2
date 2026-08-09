<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            投稿編集
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('posts.update', $post) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label>タイトル</label><br>
                        <input type="text" name="title" value="{{ old('title', $post->title) }}" class="border rounded w-full p-2">
                        @error('title')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label>カテゴリー</label><br>
                        <input type="text" name="category" value="{{ old('category', $post->category) }}" class="border rounded w-full p-2">
                        @error('category')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label>内容</label><br>
                        <textarea name="content" class="border rounded w-full p-2" rows="6">{{ old('content', $post->content) }}</textarea>
                        @error('content')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded">更新する</button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>