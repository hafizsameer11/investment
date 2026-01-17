<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Show the notification form.
     */
    public function index()
    {
        $users = User::where('role', 'user')
            ->orderBy('name')
            ->get();

        return view('admin.pages.notification.create', compact('users'));
    }

    /**
     * Send notifications to users.
     */
    public function store(Request $request)
    {
        $request->validate([
            'notification_type' => 'required|in:all_users,specific_users',
            'user_ids' => 'required_if:notification_type,specific_users|array',
            'user_ids.*' => 'exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ], [
            'notification_type.required' => 'Please select notification type.',
            'notification_type.in' => 'Invalid notification type selected.',
            'user_ids.required_if' => 'Please select at least one user.',
            'user_ids.array' => 'Invalid user selection.',
            'user_ids.*.exists' => 'One or more selected users do not exist.',
            'title.required' => 'Please enter a notification title.',
            'title.max' => 'Title must not exceed 255 characters.',
            'message.required' => 'Please enter a notification message.',
            'message.max' => 'Message must not exceed 5000 characters.',
        ]);

        try {
            $userIds = $request->notification_type === 'all_users' ? null : $request->user_ids;

            NotificationService::sendAdminNotification(
                $userIds,
                $request->title,
                $request->message
            );

            $recipientCount = $userIds ? count($userIds) : User::where('role', 'user')->count();

            return redirect()->route('admin.notifications.create')
                ->with('success', "Notification sent successfully to {$recipientCount} user(s).");

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to send notification. Please try again.');
        }
    }
}

