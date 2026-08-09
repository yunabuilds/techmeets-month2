<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $post->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <p class="text-sm text-gray-500">カテゴリー：{{ $post->category }}</p>
                <small class="text-sm text-gray-400">{{ $post->created_at->format('Y年m月d日') }}</small>

                <div class="mt-4 mb-6">
                    {{ $post->content }}
                </div>

                @if ($post->user_id === auth()->id())
                    <a href="{{ route('posts.edit', $post) }}" class="bg-yellow-500 text-white px-4 py-2 rounded">編集</a>

                    <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">削除</button>
                    </form>
                @endif

                <div class="mt-4">
                    <a href="{{ route('posts.index') }}" class="text-indigo-600">一覧に戻る</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>