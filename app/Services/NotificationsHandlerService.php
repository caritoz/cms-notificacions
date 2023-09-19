<?php

    namespace App\Services;

    use App\Contracts\NotificationsHandlerServiceContract;
    use App\Models\Comment;
    use App\Models\User;
    use Illuminate\Support\Facades\Auth;

class NotificationsHandlerService implements NotificationsHandlerServiceContract
{
    /**
     * COMMENTS rule: Comments on a post you've been writing to
     * @param $comment
     * @return void
     */
    public static function sendIfCommentPosted(Comment $comment): void
    {
        if ($userOwner = User::where('id', '!=', Auth::id())->find($comment->commentable->user_id)) {
            $userOwner->notify(new \App\Notifications\CommentPosted($comment));
        }
    }
}
