<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $notificationTypes = [];

        $notificationTypes[] = [
            'class'         => \App\Notifications\CommentPosted::class,
            'description' => "Comments on a post you've been writing to",
            'group'         => 'Comments',
            'order'         => 1,
            'created_at'    => $now,
            'updated_at'    => $now
        ];

        $notificationTypes[] = [
            'class'         => \App\Notifications\CommentMentioned::class,
            'description' => "Comments on a post you've been mentioned to",
            'group'         => 'Comments',
            'order'         => 2,
            'created_at'    => $now,
            'updated_at'    => $now
        ];

        DB::table('notification_types')->insert($notificationTypes);
    }
}
