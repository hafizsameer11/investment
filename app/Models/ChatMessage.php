<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'sender_id',
        'sender_type',
        'message',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the chat that owns this message.
     */
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * Get the user who sent this message.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get sender name (user name or guest name from chat).
     */
    public function getSenderNameAttribute()
    {
        if ($this->sender) {
            return $this->sender->name;
        }
        
        // For guest users, get name from chat
        if ($this->chat && !$this->chat->user_id) {
            return $this->chat->guest_name ?? 'Guest';
        }
        
        return 'Guest';
    }

    /**
     * Scope a query to only include unread messages.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope a query to only include read messages.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope a query to only include user messages.
     */
    public function scopeFromUser($query)
    {
        return $query->where('sender_type', 'user');
    }

    /**
     * Scope a query to only include admin messages.
     */
    public function scopeFromAdmin($query)
    {
        return $query->where('sender_type', 'admin');
    }

    /**
     * Mark the message as read.
     */
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Check if message is from admin.
     */
    public function isFromAdmin()
    {
        return $this->sender_type === 'admin';
    }

    /**
     * Check if message is from user.
     */
    public function isFromUser()
    {
        return $this->sender_type === 'user';
    }
}
