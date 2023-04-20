<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

         $user = \App\Models\User::factory()->create([
             'name' => 'John Doe',
             'email' => 'johndoe@example.com',
             'password' => Hash::make('secret')
         ]);

//        User::factory(5)->create(['account_id' => $account->id]);
        $allUserIds = (User::factory(5)->create() )->pluck('id');;

        $postIds = (Post::factory(100)
            ->create(['user_id' => $user->id]) )->pluck('id');;
        $post = $postIds->random();

        $commentModel = Comment::create([
            'user_id'               => $user->id,
            'parent_id'            => null,
            'body'                  => 'Voluptatem asperiores ab cumque eligendi excepturi neque eligendi accusantium. Est vitae et velit veritatis aperiam accusantium doloribus.',
            'commentable_id'        => $post,
            'commentable_type'    =>  Post::class,
        ]);

        $replyCommentModel = Comment::create([
            'user_id'               => $allUserIds->random(),
            'parent_id'            => $commentModel->id,
            'body'                  => 'Debitis nesciunt ut et natus et dolor necessitatibus doloremque a molestiae ut eligendi est rerum excepturi natus non quibusdam voluptatibus',
            'commentable_id'        => $post,
            'commentable_type'    =>  Post::class,
        ]);
    }
}
