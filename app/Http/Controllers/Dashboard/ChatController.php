<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\ChatStarted;
use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    /**
     * Start a new chat session.
     */
    public function startChat(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Create chat session
        $chat = Chat::create([
            'user_id' => Auth::id(),
            'guest_name' => Auth::check() ? null : $request->name,
            'guest_email' => Auth::check() ? null : $request->email,
            'status' => 'pending',
            'started_at' => now(),
        ]);

        // Create initial message
        $chatMessage = ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_id' => Auth::id(), // null for guest users
            'sender_type' => 'user',
            'message' => $request->message,
        ]);

        // Send notification to admins
        NotificationService::sendChatStartedNotification($chat);

        // Broadcast chat started event to notify admins
        event(new ChatStarted($chat));

        // Broadcast initial message
        event(new MessageSent($chatMessage));

        return response()->json([
            'success' => true,
            'chat' => [
                'id' => $chat->id,
                'status' => $chat->status,
            ],
            'message' => 'Chat started successfully',
        ]);
    }

    /**
     * Get chat details and messages.
     */
    public function getChat($id)
    {
        $chat = Chat::with(['messages.sender', 'user', 'assignedAdmin'])
            ->findOrFail($id);

        // Check if user has access to this chat
        if (Auth::check()) {
            if ($chat->user_id !== Auth::id()) {
                abort(403, 'Unauthorized access to this chat');
            }
        } else {
            // For guest users, we'd need to store chat ID in session
            // For now, we'll allow access if no user_id is set
            if ($chat->user_id !== null) {
                abort(403, 'Unauthorized access to this chat');
            }
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
                    'image_path' => $message->image_path,
                    'image_url' => $message->image_url,
                    'is_read' => $message->is_read,
                    'read_at' => $message->read_at ? $message->read_at->toIso8601String() : null,
                    'created_at' => $message->created_at->toIso8601String(),
                ];
            }),
        ]);
    }

    /**
     * Send a message in a chat.
     */
    public function sendMessage(Request $request, $id)
    {
        // Validate: either message or image is required
        $request->validate([
            'message' => 'nullable|string|max:5000',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120', // 5MB max
        ]);

        // Ensure at least one of message or image is provided
        if (!$request->has('message') && !$request->hasFile('image')) {
            return response()->json([
                'success' => false,
                'message' => 'Either a message or an image is required',
            ], 422);
        }

        $chat = Chat::findOrFail($id);

        // Check if user has access to this chat
        if (Auth::check()) {
            if ($chat->user_id !== Auth::id()) {
                abort(403, 'Unauthorized access to this chat');
            }
        } else {
            if ($chat->user_id !== null) {
                abort(403, 'Unauthorized access to this chat');
            }
        }

        // Check if chat is closed
        if ($chat->status === 'closed') {
            return response()->json([
                'success' => false,
                'message' => 'This chat has been closed',
            ], 400);
        }

        // Mark chat as active if it was pending
        if ($chat->status === 'pending') {
            $chat->markAsActive();
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'chat_' . $chat->id . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            $destinationPath = storage_path('app/public/chat-images');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            // Move uploaded file
            $image->move($destinationPath, $filename);
            
            // Store relative path for database
            $imagePath = 'chat-images/' . $filename;
        }

        // Create message
        $chatMessage = ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_id' => Auth::id(), // null for guest users
            'sender_type' => 'user',
            'message' => $request->message ?? '',
            'image_path' => $imagePath,
        ]);

        // Broadcast message
        event(new MessageSent($chatMessage));

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $chatMessage->id,
                'sender_id' => $chatMessage->sender_id,
                'sender_type' => $chatMessage->sender_type,
                'sender_name' => $chatMessage->sender->name ?? ($chatMessage->sender_id ? 'Guest' : 'Guest'),
                'message' => $chatMessage->message,
                'image_path' => $chatMessage->image_path,
                'image_url' => $chatMessage->image_url,
                'is_read' => $chatMessage->is_read,
                'read_at' => $chatMessage->read_at ? $chatMessage->read_at->toIso8601String() : null,
                'created_at' => $chatMessage->created_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * Check for existing active chat.
     */
    public function getActiveChat(Request $request)
    {
        $userId = Auth::id();
        
        // For authenticated users, check by user_id
        if ($userId) {
            $chat = Chat::where('user_id', $userId)
                ->whereIn('status', ['pending', 'active'])
                ->orderBy('created_at', 'desc')
                ->first();
                
            // Check if chat should be auto-closed
            if ($chat && $chat->shouldAutoClose(8)) {
                $chat->close();
                return response()->json([
                    'success' => true,
                    'has_active_chat' => false,
                ]);
            }
        } else {
            // For guest users, check by email from request or session
            $email = $request->input('email');
            if ($email) {
                $chat = Chat::where('guest_email', $email)
                    ->whereNull('user_id')
                    ->whereIn('status', ['pending', 'active'])
                    ->orderBy('created_at', 'desc')
                    ->first();
                    
                // Check if chat should be auto-closed
                if ($chat && $chat->shouldAutoClose(8)) {
                    $chat->close();
                    return response()->json([
                        'success' => true,
                        'has_active_chat' => false,
                    ]);
                }
            } else {
                $chat = null;
            }
        }

        if ($chat) {
            return response()->json([
                'success' => true,
                'has_active_chat' => true,
                'chat' => [
                    'id' => $chat->id,
                    'status' => $chat->status,
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'has_active_chat' => false,
        ]);
    }

    /**
     * Get unread admin message count for current user.
     */
    public function getUnreadCount(Request $request)
    {
        $userId = Auth::id();
        
        if ($userId) {
            // For authenticated users, check chats by user_id
            $unreadCount = ChatMessage::whereHas('chat', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->whereIn('status', ['pending', 'active']);
            })
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->count();
        } else {
            // For guest users, check by email from request
            $userEmail = $request->input('email');
            if ($userEmail) {
                $unreadCount = ChatMessage::whereHas('chat', function ($query) use ($userEmail) {
                    $query->where('guest_email', $userEmail)
                        ->whereNull('user_id')
                        ->whereIn('status', ['pending', 'active']);
                })
                ->where('sender_type', 'admin')
                ->where('is_read', false)
                ->count();
            } else {
                $unreadCount = 0;
            }
        }

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark admin messages as read in a chat.
     */
    public function markMessagesAsRead($id)
    {
        $chat = Chat::findOrFail($id);

        // Check if user has access to this chat
        if (Auth::check()) {
            if ($chat->user_id !== Auth::id()) {
                abort(403, 'Unauthorized access to this chat');
            }
        } else {
            if ($chat->user_id !== null) {
                abort(403, 'Unauthorized access to this chat');
            }
        }

        // Mark all admin messages in this chat as read
        $updated = ChatMessage::where('chat_id', $chat->id)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'updated_count' => $updated,
        ]);
    }

    /**
     * Get messages for a chat (AJAX polling fallback).
     */
    public function getMessages($id)
    {
        $chat = Chat::findOrFail($id);

        // Check if user has access to this chat
        if (Auth::check()) {
            if ($chat->user_id !== Auth::id()) {
                abort(403, 'Unauthorized access to this chat');
            }
        } else {
            if ($chat->user_id !== null) {
                abort(403, 'Unauthorized access to this chat');
            }
        }

        $messages = ChatMessage::where('chat_id', $chat->id)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'messages' => $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'sender_type' => $message->sender_type,
                    'sender_name' => $message->sender->name ?? ($message->sender_id ? 'Guest' : 'Guest'),
                    'message' => $message->message,
                    'image_path' => $message->image_path,
                    'image_url' => $message->image_url,
                    'is_read' => $message->is_read,
                    'read_at' => $message->read_at ? $message->read_at->toIso8601String() : null,
                    'created_at' => $message->created_at->toIso8601String(),
                ];
            }),
        ]);
    }

    /**
     * Serve a chat image stored on the public disk.
     */
    public function serveImage(string $path)
    {
        $normalizedPath = ltrim($path, '/');

        if (!str_starts_with($normalizedPath, 'chat-images/')) {
            abort(404);
        }

        if (!Storage::disk('public')->exists($normalizedPath)) {
            abort(404);
        }

        return response()->file(Storage::disk('public')->path($normalizedPath));
    }
}
