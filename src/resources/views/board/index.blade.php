<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            匿名掲示板
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- 投稿フォーム --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <form action="{{ route('board.store') }}" method="POST">
                    @csrf
                    <textarea name="content" class="border rounded w-full p-2" rows="3" placeholder="投稿内容を入力してください">{{ old('content') }}</textarea>
                    @error('content')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror

                    @auth
                        <p class="text-sm text-gray-500 mt-1">投稿者: {{ auth()->user()->name }}</p>
                    @else
                        <p class="text-sm text-gray-500 mt-1">投稿者: 名無しさん(ログインすると本名で投稿できます)</p>
                    @endauth

                    <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded mt-2">投稿する</button>
                </form>
            </div>

            {{-- 投稿一覧 --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @forelse ($boardPosts as $boardPost)
                    <div class="border-b py-4">
                        <p class="font-bold">{{ $boardPost->author_name }}</p>
                        <p>{{ $boardPost->content }}</p>
                        <small class="text-gray-400">{{ $boardPost->created_at->format('Y年m月d日 H:i') }}</small>

                        @if (auth()->check() && $boardPost->user_id === auth()->id())
                            <form action="{{ route('board.destroy', $boardPost) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 text-sm ml-2">削除</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p>まだ投稿がありません。</p>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $boardPosts->links() }}
            </div>

        </div>
    </div>
</x-app-layout>