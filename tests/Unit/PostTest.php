<?php

    namespace Tests\Unit;

    use App\Models\Post;
    use App\Models\User;
    use Database\Seeders\NotificationTypesSeeder;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Foundation\Testing\WithFaker;
    use Tests\TestCase;

    class PostTest extends TestCase
    {
        use RefreshDatabase, WithFaker;

        protected function setUp(): void
        {
            parent::setUp(); // TODO: Change the autogenerated stub

            $this->user         = User::factory()->create();
        }

        public function test_post_can_be_created()
        {
            $data = [
                'title' => $this->faker->sentence(7),
                'body' => $this->faker->sentence(200),
                'user_id'   => $this->user->id
            ];

            $post = Post::create($data);

            $this->assertInstanceOf(Post::class, $post);
            $this->assertEquals($data['title'], $post->title);
            $this->assertEquals($data['body'], $post->body);
        }

        public function test_post_can_be_soft_deleted()
        {
            $post = Post::create([
                'title' => $this->faker->sentence(7),
                'body' => $this->faker->sentence(200),
                'user_id'   => $this->user->id
            ]);

            $post->delete();

            $this->assertSoftDeleted($post);
        }
    }
