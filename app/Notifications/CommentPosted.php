<?php

namespace App\Notifications;

use App\Contracts\NotificationDataAccessor;
use App\Models\Comment;
use App\Models\Post;
use App\Traits\NotificationFormatter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentPosted extends Notification implements NotificationDataAccessor
{
    use Queueable, NotificationFormatter;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Comment $comment
    )
    {
        $this->comment = $comment->load('user', 'commentable');;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array|string
    {
        return $notifiable->viaSettings(self::class);
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("You've got comments")
            ->markdown('emails.notifications.comment-posted', ['comment' => $this->comment]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $format = 'Comments have been posted on post "%s"';
        $content = sprintf($format,
            $this->comment->commentable->title
        );

        return $this->setData($content);
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage( $this->setContent($notifiable) );
    }

    /**
     * @return Post
     */
    public function getEntity(): Post
    {
        // TODO: Implement getEntity() method.
        return $this->comment->commentable;
    }
}
