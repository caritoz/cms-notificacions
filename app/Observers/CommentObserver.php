<?php

namespace App\Observers;

use App\Events\CommentUpdatedEvent;
use App\Models\Comment;
use App\Services\NotificationsHandlerService;
use Illuminate\Support\Facades\Auth;

class CommentObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
//    public $afterCommit = true;

    /**
     * Handle the Comment "created" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function created(Comment $comment)
    {
        $user = Auth::user();

        // send a comment the comments channels
        broadcast(new CommentUpdatedEvent($user, $comment, 'add'));

        // COMMENTS rule: Comments on a post you've been writing to
        NotificationsHandlerService::sendIfCommentPosted($comment);
    }

    /**
     * Handle the Comment "updated" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function updated(Comment $comment)
    {
        $user = Auth::user();

        broadcast(new CommentUpdatedEvent($user, $comment, 'update'));
    }
    /**
     * Handle the Comment "deleted" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function deleted(Comment $comment)
    {
        $user = Auth::user();

        broadcast(new CommentUpdatedEvent($user, $comment, 'remove'));
    }

    /**
     * Handle the Comment "force deleted" event.
     *
     * @param  \App\Models\Comment  $comment
     * @return void
     */
    public function forceDeleted(Comment $comment)
    {
        $user = Auth::user();

        broadcast(new CommentUpdatedEvent($user, $comment, 'remove'));
    }
}
