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

class ChatAssigned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat;
    public $adminId;

    /**
     * Create a new event instance.
     */
    public function __construct(Chat $chat, $adminId)
    {
        $this->chat = $chat;
        $this->adminId = $adminId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->chat->id),
            new PrivateChannel('admin.chats'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'chat.assigned';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'chat' => [
                'id' => $this->chat->id,
                'assigned_to' => $this->chat->assigned_to,
                'status' => $this->chat->status,
                'admin_name' => $this->chat->assignedAdmin->name ?? null,
            ],
        ];
    }
}
