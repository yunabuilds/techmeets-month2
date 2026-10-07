<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostLikeTest extends TestCase
{
    use RefreshDatabase;

    // いいねすると、いいね数が1増える
    public function test_user_can_like_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->post("/posts/{$post->id}/like");

        $response->assertRedirect(route('posts.show', $post));
        $this->assertEquals(1, $post->fresh()->likes_count);
    }

    // ログインしていないと、いいねできない
    public function test_guest_cannot_like_post()
    {
        $post = Post::factory()->create();

        $response = $this->post("/posts/{$post->id}/like");

        $response->assertRedirect('/login');
    }
}