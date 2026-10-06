<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    // 作成：ログインユーザーが投稿でき、DBに保存される
    public function test_authenticated_user_can_create_post()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/posts', [
            'title'    => 'Test Post',
            'content'  => 'This is a test post.',
            'category' => 'tech',
        ]);

        $this->assertDatabaseHas('posts', [
            'title'   => 'Test Post',
            'user_id' => $user->id,
        ]);
        $response->assertRedirect(route('posts.show', Post::first()));
    }

    // 表示：投稿一覧が見られる
    public function test_user_can_view_posts()
    {
        $user = User::factory()->create();
        Post::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/posts');

        $response->assertStatus(200);
        $response->assertViewIs('posts.index');
        $response->assertViewHas('posts');
    }

    // 表示：投稿の詳細が見られる
    public function test_user_can_view_single_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->get("/posts/{$post->id}");

        $response->assertStatus(200);
        $response->assertSee($post->title);
    }

    // 更新：自分の投稿を編集できる
    public function test_user_can_update_own_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put("/posts/{$post->id}", [
            'title'    => 'Updated Title',
            'content'  => 'Updated content.',
            'category' => 'news',
        ]);

        $response->assertRedirect(route('posts.show', $post));
        $this->assertDatabaseHas('posts', [
            'id'    => $post->id,
            'title' => 'Updated Title',
        ]);
    }

    // 削除：自分の投稿を削除できる
    public function test_user_can_delete_own_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete("/posts/{$post->id}");

        $response->assertRedirect(route('posts.index'));
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}