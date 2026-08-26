<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository
{
    // 全件取得（最新順・ページネーション付き）
    public function getAll()
    {
        return Post::latest()->paginate(10);
    }

    // IDで1件取得
    public function findById(string $id)
    {
        return Post::findOrFail($id);
    }

    public function create(array $data)
    {
        return Post::create($data);
    }

    public function update(Post $post, array $data)
    {
        $post->update($data);
        return $post;
    }

    public function delete(Post $post)
    {
        return $post->delete();
    }
}