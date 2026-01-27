<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'guest_name',
        'guest_email',
        'status',
        'assigned_to',
        'started_at',
        'closed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the chat (if authenticated).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin assigned to this chat.
     */
    public function assignedAdmin()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get all messages for this chat.
     */
    public function messages()
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get the latest message for this chat.
     */
    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class)->latestOfMany();
    }

    /**
     * Scope a query to only include pending chats.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include active chats.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include closed chats.
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    /**
     * Scope a query to only include unassigned chats.
     */
    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_to');
    }

    /**
     * Get the user's name (authenticated or guest).
     */
    public function getUserNameAttribute()
    {
        return $this->user ? $this->user->name : $this->guest_name;
    }

    /**
     * Get the user's email (authenticated or guest).
     */
    public function getUserEmailAttribute()
    {
        return $this->user ? $this->user->email : $this->guest_email;
    }

    /**
     * Mark chat as active.
     */
    public function markAsActive()
    {
        $this->update([
            'status' => 'active',
            'started_at' => $this->started_at ?? now(),
        ]);
    }

    /**
     * Close the chat.
     */
    public function close()
    {
        $this->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);
    }

    /**
     * Check if chat has unread messages for a specific user.
     */
    public function hasUnreadMessagesFor($userId)
    {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->exists();
    }

    /**
     * Get unread message count for a specific user.
     */
    public function getUnreadCountFor($userId)
    {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Check if chat should be auto-closed (2-3 hours of inactivity).
     */
    public function shouldAutoClose($hours = 2.5)
    {
        if ($this->status === 'closed') {
            return false;
        }

        $lastMessage = $this->latestMessage;
        if (!$lastMessage) {
            // If no messages, check from start time
            $lastActivity = $this->started_at ?? $this->created_at;
        } else {
            $lastActivity = $lastMessage->created_at;
        }

        $hoursSinceLastActivity = now()->diffInHours($lastActivity);
        return $hoursSinceLastActivity >= $hours;
    }

    /**
     * Auto-close old chats (2.5 hours of inactivity).
     */
    public static function autoCloseOldChats($hours = 2.5)
    {
        $chats = self::whereIn('status', ['pending', 'active'])
            ->with('latestMessage')
            ->get();

        $closedCount = 0;
        foreach ($chats as $chat) {
            if ($chat->shouldAutoClose($hours)) {
                $chat->close();
                $closedCount++;
            }
        }

        return $closedCount;
    }
}
