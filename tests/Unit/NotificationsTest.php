<?php

    namespace Tests\Unit;

    use App\Models\NotificationSettings;
    use App\Models\Post;
    use App\Models\User;
    use App\Notifications\CommentPosted;
    use Database\Seeders\NotificationTypesSeeder;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Foundation\Testing\WithFaker;
    use Illuminate\Support\Facades\Event;
    use Illuminate\Support\Facades\Mail;
    use Illuminate\Support\Facades\Notification;
    use Illuminate\Support\Str;
    use Tests\TestCase;

    class NotificationsTest extends TestCase
    {
        use RefreshDatabase, WithFaker;

        protected function setUp(): void
        {
            parent::setUp(); // TODO: Change the autogenerated stub

            $this->seed(NotificationTypesSeeder::class);

            $this->user         = User::factory()->create();
            $this->otherUser = User::factory()->create();
        }

        /**
         * @param string $class
         * @param array $channel
         * @param string|null $observer
         * @return void
         */
        private function createNotificationSettings(string $class, array $channel, string|null $observer = null)
        {
            Notification::fake();
            Mail::fake();

            if ($observer)
                Event::fake([$observer]);

            foreach ([$this->user, $this->otherUser] as $user)
            {
                NotificationSettings::factory()->create([
                    'user_id'                       => $user->id,
                    'notification_types_id'     => \App\Models\NotificationTypes::where('class', $class)->first()->id,
                    'channel'                      => $channel
                ]);
            }
        }

        public function test_after_user_write_comment_into_post_check_if_sent_by_database()
        {
            $this->createNotificationSettings(CommentPosted::class, ['database'], \App\Observers\CommentObserver::class);

            // needs a post
            $title = $this->faker->sentence(7);
            $post = $this->user->posts()->create([
                'title' => $title,
            ]);
            Notification::assertNothingSent();

            $request = [
                'body'                      =>  $this->faker->sentence(20),
                'commentable_type'   =>  Post::class,
                'commentable_id'       =>  $post->id,
            ];
            $response = $this->actingAs($this->otherUser)
                    ->post('/comments', $request);
            $response->assertSessionDoesntHaveErrors();
            $this->assertDatabaseHas('comments', ['commentable_id' => $post->id]);

            // should trigger a notification after that
            Notification::assertSentTo($this->user, CommentPosted::class, function ($notification, $channels) use($title) {
                $content = Str::limit('Comments have been posted on post "' . $title .'"', 80);
                $this->assertContains('database', $channels);

                $databaseNotification = $notification->toArray($this->user);
                $this->assertEquals($content, $databaseNotification['message']['content'],);

                return true;
            });
        }

        public function test_after_user_write_comment_into_post_check_if_sent_by_mail()
        {
            $this->createNotificationSettings(CommentPosted::class, ['mail'], \App\Observers\CommentObserver::class);

            // needs a post
            $title = $this->faker->sentence(7);
            $post = $this->user->posts()->create([
                'title' => $title,
            ]);
            Notification::assertNothingSent();

            $request = [
                'body'                      =>  $this->faker->sentence(20),
                'commentable_type'   =>  Post::class,
                'commentable_id'       =>  $post->id,
            ];
            $response = $this->actingAs($this->otherUser)
                ->post('/comments', $request);
            $response->assertSessionDoesntHaveErrors();
            $this->assertDatabaseHas('comments', ['commentable_id' => $post->id]);

            // should trigger a notification after that
            Notification::assertSentTo($this->user, CommentPosted::class, function ($notification, $channels) use($title) {
                $email_subject = "You've got comments";
                $this->assertContains('mail', $channels);

                $mailNotification = (object)$notification->toMail($this->user);
                $this->assertEquals($email_subject, $mailNotification->subject,);

                return true;
            });
        }
    }
