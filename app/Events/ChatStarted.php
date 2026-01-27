<?php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatStarted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat;

    /**
     * Create a new event instance.
     */
    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin.chats'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'chat.started';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'chat' => [
                'id' => $this->chat->id,
                'user_id' => $this->chat->user_id,
                'guest_name' => $this->chat->guest_name,
                'guest_email' => $this->chat->guest_email,
                'status' => $this->chat->status,
                'assigned_to' => $this->chat->assigned_to,
                'user_name' => $this->chat->user_name,
                'user_email' => $this->chat->user_email,
                'created_at' => $this->chat->created_at->toIso8601String(),
            ],
        ];
    }
}
