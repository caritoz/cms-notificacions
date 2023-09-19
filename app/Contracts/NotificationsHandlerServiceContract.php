<?php

    namespace App\Contracts;

    use App\Models\Comment;

interface NotificationsHandlerServiceContract
{
    public static function sendIfCommentPosted(Comment $comment): void;
}
