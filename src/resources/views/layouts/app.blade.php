<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>
        <link rel="stylesheet" href="/css/app.css">
    </head>
    <body>
        <header>
            <nav>
                <a href="/">ホーム</a>
                <a href="/posts">投稿一覧</a>
                <a href="/tasks">タスク一覧</a>
            </nav>
        </header>

        <main>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>

        <footer>
            © 2025 My App
        </footer>
    </body>
</html>