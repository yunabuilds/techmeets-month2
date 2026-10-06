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
    // ===== エッジケース =====

    // タイトルがちょうど200文字ならOK（境界値）
    public function test_title_with_200_characters_is_valid()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/posts', [
            'title'    => str_repeat('a', 200),
            'content'  => 'content',
            'category' => 'tech',
        ]);

        $response->assertSessionHasNoErrors();
    }

    // タイトルが201文字ならエラー（境界値の1つ外）
    public function test_title_with_201_characters_is_invalid()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/posts', [
            'title'    => str_repeat('a', 201),
            'content'  => 'content',
            'category' => 'tech',
        ]);

        $response->assertSessionHasErrors('title');
    }

    // 存在しない投稿を開くと404
    public function test_viewing_nonexistent_post_returns_404()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/posts/999');

        $response->assertStatus(404);
    }

    // 投稿が0件でも一覧が表示できる
    public function test_posts_index_works_with_no_posts()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/posts');

        $response->assertStatus(200);
    }

    // ===== バリデーション =====

    public function test_title_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/posts', [
            'title'    => '',
            'content'  => 'content',
            'category' => 'tech',
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_content_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/posts', [
            'title'    => 'Title',
            'content'  => '',
            'category' => 'tech',
        ]);

        $response->assertSessionHasErrors('content');
    }

    public function test_category_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/posts', [
            'title'    => 'Title',
            'content'  => 'content',
            'category' => '',
        ]);

        $response->assertSessionHasErrors('category');
    }
}