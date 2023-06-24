<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Reaction
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $likes;
    public $post_id;
    public function __construct($likes, $post_id)
    {
        $this->likes = $likes;
        $this->post_id = $post_id;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('react-post'),
        ];
    }
    public function broadcastAs(): string
    {
        return 'react-post';
    }
}
