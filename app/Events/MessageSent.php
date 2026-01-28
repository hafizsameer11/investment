<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(ChatMessage $message)
    {
        // Load chat relationship if not already loaded
        if (!$message->relationLoaded('chat')) {
            $message->load('chat');
        }
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('chat.' . $this->message->chat_id),
        ];
        
        // Also broadcast to user-specific channel if admin sends message
        // This allows the badge to update even when chat window is closed
        if ($this->message->sender_type === 'admin') {
            $chat = $this->message->chat;
            if ($chat && $chat->user_id) {
                // Authenticated user
                $channels[] = new PrivateChannel('user.' . $chat->user_id . '.chats');
            }
        }
        
        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'chat_id' => $this->message->chat_id,
                'sender_id' => $this->message->sender_id,
                'sender_type' => $this->message->sender_type,
                'sender_name' => $this->message->sender->name ?? ($this->message->sender_id ? 'Guest' : 'Guest'),
                'message' => $this->message->message,
                'image_path' => $this->message->image_path,
                'image_url' => $this->message->image_url,
                'is_read' => $this->message->is_read,
                'read_at' => $this->message->read_at ? $this->message->read_at->toIso8601String() : null,
                'created_at' => $this->message->created_at->toIso8601String(),
            ],
        ];
    }
}
