<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WithdrawalController extends Controller
{
    /**
     * Display a listing of withdrawals.
     */
    public function index(Request $request)
    {
        $query = Withdrawal::with(['user', 'paymentMethod', 'approver'])
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->get();

        return view('admin.pages.withdrawal.index', compact('withdrawals'));
    }

    /**
     * Display the specified withdrawal.
     */
    public function show($id)
    {
        $withdrawal = Withdrawal::with(['user', 'paymentMethod', 'approver'])
            ->findOrFail($id);

        return view('admin.pages.withdrawal.show', compact('withdrawal'));
    }

    /**
     * Approve a withdrawal and upload proof image.
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
            'admin_proof_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ], [
            'admin_proof_image.required' => 'Please upload a proof image.',
            'admin_proof_image.image' => 'Proof must be an image file.',
            'admin_proof_image.max' => 'Proof image must not exceed 5MB.',
        ]);

        try {
            DB::beginTransaction();

            $withdrawal = Withdrawal::findOrFail($id);

            // Check if withdrawal is already processed
            if ($withdrawal->status !== 'pending') {
                return redirect()->back()
                    ->with('error', 'This withdrawal has already been processed.');
            }

            // Handle file upload
            $proofImagePath = null;
            if ($request->hasFile('admin_proof_image')) {
                $file = $request->file('admin_proof_image');
                $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
                
                // Create directory if it doesn't exist
                $directory = public_path('assets/withdrawals/admin-proofs');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                // Move the file
                if (!$file->move($directory, $fileName)) {
                    return redirect()->back()
                        ->with('error', 'Failed to upload proof image. Please try again.');
                }
                
                $proofImagePath = 'assets/withdrawals/admin-proofs/' . $fileName;
            }

            // Update withdrawal status
            $withdrawal->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'admin_notes' => $request->admin_notes,
                'admin_proof_image' => $proofImagePath,
            ]);

            DB::commit();

            return redirect()->route('admin.withdrawals.index')
                ->with('success', 'Withdrawal approved successfully. Proof image has been uploaded.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Withdrawal Approval Error: ' . $e->getMessage());

            // Delete uploaded file if withdrawal update failed
            if (isset($proofImagePath) && file_exists(public_path($proofImagePath))) {
                unlink(public_path($proofImagePath));
            }

            return redirect()->back()
                ->with('error', 'Failed to approve withdrawal. Please try again.');
        }
    }

    /**
     * Reject a withdrawal.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ], [
            'admin_notes.required' => 'Please provide a reason for rejection.',
        ]);

        try {
            DB::beginTransaction();

            $withdrawal = Withdrawal::with('user')->findOrFail($id);

            // Check if withdrawal is already processed
            if ($withdrawal->status !== 'pending') {
                return redirect()->back()
                    ->with('error', 'This withdrawal has already been processed.');
            }

            // Refund the amount back to user's net_balance
            // We'll add it back to fund_wallet
            $user = $withdrawal->user;
            $user->fund_wallet += $withdrawal->amount;
            $user->updateNetBalance();

            // Update withdrawal status
            $withdrawal->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'admin_notes' => $request->admin_notes,
            ]);

            DB::commit();

            return redirect()->route('admin.withdrawals.index')
                ->with('warning', 'Withdrawal has been rejected. Amount has been refunded to user.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Withdrawal Rejection Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to reject withdrawal. Please try again.');
        }
    }
}
