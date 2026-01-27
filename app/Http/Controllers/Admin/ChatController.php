<?php

namespace App\Http\Controllers\Admin;

use App\Events\ChatAssigned;
use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display a listing of chats.
     */
    public function index(Request $request)
    {
        $query = Chat::with(['user', 'assignedAdmin', 'latestMessage']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by assigned admin
        if ($request->has('assigned_to')) {
            if ($request->assigned_to === 'unassigned') {
                $query->whereNull('assigned_to');
            } else {
                $query->where('assigned_to', $request->assigned_to);
            }
        }

        $chats = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.pages.chats.index', compact('chats'));
    }

    /**
     * Display the specified chat.
     */
    public function show($id)
    {
        $chat = Chat::with(['messages.sender', 'user', 'assignedAdmin'])
            ->findOrFail($id);

        // Mark admin messages as read
        ChatMessage::where('chat_id', $chat->id)
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return view('admin.pages.chats.show', compact('chat'));
    }

    /**
     * Assign chat to current admin.
     */
    public function assignChat(Request $request, $id)
    {
        $chat = Chat::findOrFail($id);

        if ($chat->assigned_to && $chat->assigned_to !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'This chat is already assigned to another admin',
            ], 400);
        }

        $chat->update([
            'assigned_to' => Auth::id(),
            'status' => 'active',
        ]);

        // Broadcast assignment
        event(new ChatAssigned($chat, Auth::id()));

        return response()->json([
            'success' => true,
            'message' => 'Chat assigned successfully',
            'chat' => [
                'id' => $chat->id,
                'assigned_to' => $chat->assigned_to,
                'status' => $chat->status,
            ],
        ]);
    }

    /**
     * Send a message as admin.
     */
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $chat = Chat::findOrFail($id);

        // Check if chat is closed
        if ($chat->status === 'closed') {
            return response()->json([
                'success' => false,
                'message' => 'This chat has been closed',
            ], 400);
        }

        // Auto-assign if not assigned
        if (!$chat->assigned_to) {
            $chat->update([
                'assigned_to' => Auth::id(),
                'status' => 'active',
            ]);
        }

        // Mark all user messages in this chat as read (since admin is replying)
        ChatMessage::where('chat_id', $chat->id)
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        // Create message
        $chatMessage = ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_id' => Auth::id(),
            'sender_type' => 'admin',
            'message' => $request->message,
            'is_read' => false, // User hasn't read it yet
        ]);

        // Broadcast message
        event(new MessageSent($chatMessage));
        
        // Broadcast read status update for user messages
        event(new \App\Events\MessagesRead($chat->id));

        // Send notification to user if authenticated
        if ($chat->user_id) {
            NotificationService::sendChatReplyNotification($chat->user, $chat);
        }

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $chatMessage->id,
                'sender_id' => $chatMessage->sender_id,
                'sender_type' => $chatMessage->sender_type,
                'sender_name' => $chatMessage->sender->name,
                'message' => $chatMessage->message,
                'is_read' => $chatMessage->is_read,
                'read_at' => $chatMessage->read_at ? $chatMessage->read_at->toIso8601String() : null,
                'created_at' => $chatMessage->created_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * Close a chat.
     */
    public function closeChat(Request $request, $id)
    {
        $chat = Chat::findOrFail($id);

        if ($chat->status === 'closed') {
            return response()->json([
                'success' => false,
                'message' => 'This chat is already closed',
            ], 400);
        }

        $chat->close();

        return response()->json([
            'success' => true,
            'message' => 'Chat closed successfully',
            'chat' => [
                'id' => $chat->id,
                'status' => $chat->status,
            ],
        ]);
    }

    /**
     * Get unread chat count for notifications.
     */
    public function getUnreadCount()
    {
        $unreadCount = Chat::where('status', 'pending')
            ->orWhere(function ($query) {
                $query->where('status', 'active')
                    ->whereHas('messages', function ($q) {
                        $q->where('sender_type', 'user')
                            ->where('is_read', false);
                    });
            })
            ->count();

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Get chat data for AJAX requests.
     */
    public function getChat($id)
    {
        $chat = Chat::with(['messages.sender', 'user', 'assignedAdmin'])
            ->findOrFail($id);

        // Mark all user messages as read when admin views the chat
        $updated = ChatMessage::where('chat_id', $chat->id)
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
            
        // Broadcast read status if messages were updated
        if ($updated > 0) {
            event(new \App\Events\MessagesRead($chat->id));
        }

        return response()->json([
            'success' => true,
            'chat' => [
                'id' => $chat->id,
                'status' => $chat->status,
                'assigned_to' => $chat->assigned_to,
                'user_name' => $chat->user_name,
                'user_email' => $chat->user_email,
                'created_at' => $chat->created_at->toIso8601String(),
            ],
            'messages' => $chat->messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'sender_type' => $message->sender_type,
                    'sender_name' => $message->sender->name ?? ($message->sender_id ? 'Guest' : 'Guest'),
                    'message' => $message->message,
                    'is_read' => $message->is_read,
                    'read_at' => $message->read_at ? $message->read_at->toIso8601String() : null,
                    'created_at' => $message->created_at->toIso8601String(),
                ];
            }),
        ]);
    }
}
