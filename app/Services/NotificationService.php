<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\User;
use App\Models\RewardLevel;
use App\Models\Chat;

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

    /**
     * Send notification when reward level is completed
     */
    public static function sendRewardLevelCompleted(User $user, RewardLevel $level)
    {
        $rewardAmount = number_format($level->reward_amount, 2);
        $investmentRequired = number_format($level->investment_required, 2);
        
        Notification::create([
            'user_id' => $user->id,
            'type' => 'reward_level_completed',
            'title' => 'Reward Level Completed',
            'message' => "Hi {$user->name}, Congratulations! You have completed the {$level->level_name} level. Your total referral investment has reached \${$investmentRequired}. You can now claim your reward of \${$rewardAmount}.",
            'related_id' => $level->id,
            'related_type' => RewardLevel::class,
        ]);
    }

    /**
     * Send notification when crypto deposit is submitted
     */
    public static function sendCryptoDepositSubmitted(Deposit $deposit)
    {
        $user = $deposit->user;
        $amount = number_format($deposit->amount, 2);
        $network = $deposit->cryptoWallet ? $deposit->cryptoWallet->network_display_name : 'Crypto';
        
        Notification::create([
            'user_id' => $user->id,
            'type' => 'crypto_deposit_submitted',
            'title' => 'Crypto Deposit Submitted',
            'message' => "Hi {$user->name}, Your crypto deposit of \${$amount} via {$network} has been submitted successfully. Your request will be reviewed and you will receive a response within 24 hours.",
            'related_id' => $deposit->id,
            'related_type' => Deposit::class,
        ]);
    }

    /**
     * Send notification to admins when a new chat is started
     */
    public static function sendChatStartedNotification(Chat $chat)
    {
        $admins = User::where('role', 'admin')->get();
        $userName = $chat->user_name;
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'chat_started',
                'title' => 'New Chat Started',
                'message' => "A new chat has been started by {$userName}. Please check the chat management page.",
                'related_id' => $chat->id,
                'related_type' => Chat::class,
            ]);
        }
    }

    /**
     * Send notification to user when admin replies to chat
     */
    public static function sendChatReplyNotification(User $user, Chat $chat)
    {
        Notification::create([
            'user_id' => $user->id,
            'type' => 'chat_reply',
            'title' => 'New Reply in Chat',
            'message' => "You have received a new reply in your chat. Click to view the conversation.",
            'related_id' => $chat->id,
            'related_type' => Chat::class,
        ]);
    }
}

