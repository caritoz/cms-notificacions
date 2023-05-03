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
        $faker =\Faker\Factory::create();

        // \App\Models\User::factory(10)->create();

         $user = \App\Models\User::factory()->create([
             'name' => 'John Doe',
             'email' => 'johndoe@example.com',
             'password' => Hash::make('secret')
         ]);

        $user2 = \App\Models\User::factory()->create([
            'name' => 'Sam Doe',
            'email' => 'samdoe@example.com',
            'password' => Hash::make('secret')
        ]);

//        User::factory(5)->create(['account_id' => $account->id]);
        $allUserIds = (User::factory(5)->create() )->pluck('id');;

        ( Post::factory(11)->create( ['user_id' => $user2->id] ) )->pluck('id');;

        $postIds = (Post::factory(9)->create( ['user_id' => $user->id] ) )->pluck('id');;
        $post = $postIds->random();

        $commentModel = Comment::create([
            'user_id'               => $user->id,
            'parent_id'            => null,
            'body'                  => $faker->realText(100),
            'commentable_id'        => $post,
            'commentable_type'    =>  Post::class,
        ]);

        $replyCommentModel = Comment::create([
            'user_id'               => $allUserIds->random(),
            'parent_id'            => $commentModel->id,
            'body'                  => $faker->realText(110),
            'commentable_id'        => $post,
            'commentable_type'    =>  Post::class,
        ]);

        $this->call([
            NotificationTypesSeeder::class
        ]);

        // pre-settings
        $user2->notificationSettings()->create( [
            'notification_types_id' => 1,
            'channel'   => ['mail', 'broadcast']
        ]);
        $user2->notificationSettings()->create( [
            'notification_types_id' => 2,
            'channel'   => ['broadcast']
        ]);

        $user->notificationSettings()->create( [
            'notification_types_id' => 1,
            'channel'   => [ 'broadcast']
        ]);
    }
}
