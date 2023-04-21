<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    public function test_can_add_comment_to_post()
    {
        $user = User::factory()->create();
        $response = $this
            ->actingAs($user)
            ->get('/profile');
        $response->assertOk();

        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $attributes = [
            'body'                      =>  Str::random(20),
            'commentable_type'   =>  Post::class,
            'commentable_id'       =>  null,
        ];

        // check authorization
        $response = $this
                    ->actingAs($user)
                     ->post('/comments', $attributes);
        $response
            ->assertSessionHasErrors('commentable_id');

        // should be work!
        $attributes['commentable_id'] = $post->id;
        $response = $this->actingAs($user)
                                ->post('/comments', $attributes);
        $response->assertSessionDoesntHaveErrors();
    }
}
