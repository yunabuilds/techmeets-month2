<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            投稿詳細
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <p class="font-bold">{{ $boardPost->author_name }}</p>
                <p>{{ $boardPost->content }}</p>
                <small class="text-gray-400">{{ $boardPost->created_at->format('Y年m月d日 H:i') }}</small>

                <div class="mt-4">
                    <a href="{{ route('board.index') }}" class="text-indigo-600">掲示板トップに戻る</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>