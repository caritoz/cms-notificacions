<?php

namespace App\Events;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, Queueable;

    /**
     * Create a new event instance.
     */
    public function __construct( public $user,
                                 public Comment $comment,
                                 public string $action
    ){
        $this->comment  = $comment->load('user'); // silly!!!!!

        // should send notifications to another users but it seems like axios has an issue
        $this->dontBroadcastToCurrentUser();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('comments.' . $this->comment->type. '.' . $this->comment->commentable_id);
    }
}
