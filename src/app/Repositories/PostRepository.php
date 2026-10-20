<?php

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;  // ★追加

class PostRepository
{
    // 全件取得（最新順・ページネーション付き・キャッシュあり）
    public function getAll()
    {
        // ★今何ページ目かを調べる（?page=2 なら 2。なければ 1）
        $page = (int) request('page', 1);

        // ★「posts」タグをつけて、ページごとに10分間作り置きする
        return Cache::tags('posts')->remember("posts_index_page_{$page}", now()->addMinutes(10), function () {
            return Post::with('user')->latest()->paginate(10);
        });
    }

    // IDで1件取得
    public function findById(string $id)
    {
        return Post::findOrFail($id);
    }

    public function create(array $data)
    {
        $post = Post::create($data);
        Cache::tags('posts')->flush();  // ★投稿が増えたので、一覧の作り置きを全部捨てる
        return $post;
    }

    public function update(Post $post, array $data)
    {
        $post->update($data);
        Cache::tags('posts')->flush();  // ★内容が変わったので捨てる
        return $post;
    }

    public function delete(Post $post)
    {
        $result = $post->delete();
        Cache::tags('posts')->flush();  // ★投稿が減ったので捨てる
        return $result;
    }
}