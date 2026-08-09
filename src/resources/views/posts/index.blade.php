<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            投稿一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4">
                <a href="{{ route('posts.create') }}" class="bg-indigo-500 text-white px-4 py-2 rounded">
                    新規投稿
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @forelse ($posts as $post)
                    <article class="border-b py-4">
                        <h2>
                            <a href="{{ route('posts.show', $post) }}" class="text-lg font-bold text-indigo-600">
                                {{ $post->title }}
                            </a>
                        </h2>
                        <p>{{ Str::limit($post->content, 100) }}</p>
                        <small class="text-sm text-gray-500">
                            投稿者: {{ $post->user->name ?? 'Unknown' }} |
                            {{ $post->created_at->format('Y年m月d日') }}
                        </small>
                    </article>
                @empty
                    <p>まだ投稿がありません。</p>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $posts->links() }}
            </div>

        </div>
    </div>
</x-app-layout>