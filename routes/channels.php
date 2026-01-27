<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Chat channel - users and assigned admin can access
Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    $chat = \App\Models\Chat::find($chatId);
    
    if (!$chat) {
        return false;
    }
    
    // User can access if they own the chat
    if ($chat->user_id && $chat->user_id === $user->id) {
        return true;
    }
    
    // Admin can access if assigned or if they are admin
    if ($user->role === 'admin') {
        return true;
    }
    
    return false;
});

// Admin chats channel - only admins can access
Broadcast::channel('admin.chats', function ($user) {
    return $user->role === 'admin';
});
