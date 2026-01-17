<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DepositController extends Controller
{
    /**
     * Display a listing of deposits.
     */
    public function index(Request $request)
    {
        $query = Deposit::with(['user', 'paymentMethod', 'approver'])
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $deposits = $query->get();

        return view('admin.pages.deposit.index', compact('deposits'));
    }

    /**
     * Display the specified deposit.
     */
    public function show($id)
    {
        $deposit = Deposit::with(['user', 'paymentMethod', 'approver'])
            ->findOrFail($id);

        return view('admin.pages.deposit.show', compact('deposit'));
    }

    /**
     * Approve a deposit and update user wallet.
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $deposit = Deposit::with('user')->findOrFail($id);

            // Check if deposit is already processed
            if ($deposit->status !== 'pending') {
                return redirect()->back()
                    ->with('error', 'This deposit has already been processed.');
            }

            // Update deposit status
            $deposit->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'admin_notes' => $request->admin_notes,
            ]);

            // Update user wallet and total invested
            $user = $deposit->user;
            $user->fund_wallet += $deposit->amount;
            // Increment total invested (this should only increase, never decrease)
            $user->total_invested += $deposit->amount;
            $user->updateNetBalance(); // This will save the user

            // Send notification to user
            NotificationService::sendDepositApproved($deposit);

            DB::commit();

            return redirect()->route('admin.deposits.index')
                ->with('success', 'Deposit approved successfully. User wallet has been updated.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Deposit Approval Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to approve deposit. Please try again.');
        }
    }

    /**
     * Reject a deposit.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ], [
            'admin_notes.required' => 'Please provide a reason for rejection.',
        ]);

        try {
            $deposit = Deposit::findOrFail($id);

            // Check if deposit is already processed
            if ($deposit->status !== 'pending') {
                return redirect()->back()
                    ->with('error', 'This deposit has already been processed.');
            }

            // Update deposit status
            $deposit->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'admin_notes' => $request->admin_notes,
            ]);

            // Send notification to user
            NotificationService::sendDepositRejected($deposit);

            return redirect()->route('admin.deposits.index')
                ->with('warning', 'Deposit has been rejected.');

        } catch (\Exception $e) {
            Log::error('Deposit Rejection Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to reject deposit. Please try again.');
        }
    }
}
