<?php

namespace App\Http\View\Composers;

use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Chat;
use Illuminate\View\View;

class AdminSidebarComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        // Get pending deposits count
        $pendingDepositsCount = Deposit::where('status', 'pending')->count();
        
        // Get pending withdrawals count
        $pendingWithdrawalsCount = Withdrawal::where('status', 'pending')->count();
        
        // Get unread chats count (pending chats or active chats with unread messages)
        $unreadChatsCount = Chat::where('status', 'pending')
            ->orWhere(function ($query) {
                $query->where('status', 'active')
                    ->whereHas('messages', function ($q) {
                        $q->where('sender_type', 'user')
                            ->where('is_read', false);
                    });
            })
            ->count();
        
        $view->with([
            'pendingDepositsCount' => $pendingDepositsCount,
            'pendingWithdrawalsCount' => $pendingWithdrawalsCount,
            'unreadChatsCount' => $unreadChatsCount,
        ]);
    }
}

