<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\User;

class NotificationService
{
    /**
     * Send notification when deposit is approved
     */
    public static function sendDepositApproved(Deposit $deposit)
    {
        $user = $deposit->user;
        $amount = number_format($deposit->amount, 2);
        
        Notification::create([
            'user_id' => $user->id,
            'type' => 'deposit_approved',
            'title' => 'Deposit Approved',
            'message' => "Hi {$user->name}, Your deposit of \${$amount} has been approved and added to your fund wallet.",
            'related_id' => $deposit->id,
            'related_type' => Deposit::class,
        ]);
    }

    /**
     * Send notification when deposit is rejected
     */
    public static function sendDepositRejected(Deposit $deposit)
    {
        $user = $deposit->user;
        $amount = number_format($deposit->amount, 2);
        $reason = $deposit->admin_notes ?? 'No reason provided';
        
        Notification::create([
            'user_id' => $user->id,
            'type' => 'deposit_rejected',
            'title' => 'Deposit Rejected',
            'message' => "Hi {$user->name}, Your deposit of \${$amount} has been rejected. Reason: {$reason}",
            'related_id' => $deposit->id,
            'related_type' => Deposit::class,
        ]);
    }

    /**
     * Send notification when withdrawal is approved
     */
    public static function sendWithdrawalApproved(Withdrawal $withdrawal)
    {
        $user = $withdrawal->user;
        $amount = number_format($withdrawal->amount, 2);
        
        Notification::create([
            'user_id' => $user->id,
            'type' => 'withdrawal_approved',
            'title' => 'Withdrawal Approved',
            'message' => "Hi {$user->name}, Your withdrawal request of \${$amount} has been approved. Proof has been uploaded.",
            'related_id' => $withdrawal->id,
            'related_type' => Withdrawal::class,
        ]);
    }

    /**
     * Send notification when withdrawal is rejected
     */
    public static function sendWithdrawalRejected(Withdrawal $withdrawal)
    {
        $user = $withdrawal->user;
        $amount = number_format($withdrawal->amount, 2);
        $reason = $withdrawal->admin_notes ?? 'No reason provided';
        
        Notification::create([
            'user_id' => $user->id,
            'type' => 'withdrawal_rejected',
            'title' => 'Withdrawal Rejected',
            'message' => "Hi {$user->name}, Your withdrawal request of \${$amount} has been rejected. Reason: {$reason}. The amount has been refunded to your wallet.",
            'related_id' => $withdrawal->id,
            'related_type' => Withdrawal::class,
        ]);
    }

    /**
     * Send admin notification to multiple users
     * 
     * @param array|int $userIds Array of user IDs or single user ID, or null for all users
     * @param string $title Notification title
     * @param string $message Notification message
     */
    public static function sendAdminNotification($userIds, string $title, string $message)
    {
        // If userIds is null or empty, send to all users
        if (empty($userIds)) {
            $users = User::where('role', 'user')->get();
            $userIds = $users->pluck('id')->toArray();
        } elseif (!is_array($userIds)) {
            $userIds = [$userIds];
        }

        $notifications = [];
        $type = count($userIds) === 1 ? 'admin_targeted' : 'admin_broadcast';

        foreach ($userIds as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Bulk insert for better performance
        Notification::insert($notifications);
    }
}

