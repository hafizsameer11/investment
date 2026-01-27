<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Models\Transaction;
use App\Models\CurrencyConversion;
use App\Services\NotificationService;
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

        // Get active currency conversion rate (fallback to any rate if no active one)
        $currencyConversion = CurrencyConversion::where('is_active', true)->first();
        if (!$currencyConversion) {
            $currencyConversion = CurrencyConversion::first();
        }
        $conversionRate = $currencyConversion ? (float) $currencyConversion->rate : 0;

        return view('admin.pages.withdrawal.index', compact('withdrawals', 'conversionRate'));
    }

    /**
     * Display the specified withdrawal.
     */
    public function show($id)
    {
        $withdrawal = Withdrawal::with(['user', 'paymentMethod', 'approver'])
            ->findOrFail($id);

        // Get active currency conversion rate (fallback to any rate if no active one)
        $currencyConversion = CurrencyConversion::where('is_active', true)->first();
        if (!$currencyConversion) {
            $currencyConversion = CurrencyConversion::first();
        }
        $conversionRate = $currencyConversion ? (float) $currencyConversion->rate : 0;
        $pkrAmount = $conversionRate > 0 ? ($withdrawal->amount * $conversionRate) : 0;

        return view('admin.pages.withdrawal.show', compact('withdrawal', 'conversionRate', 'pkrAmount'));
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

            // Create transaction record
            $paymentMethodName = $withdrawal->paymentMethod ? $withdrawal->paymentMethod->name : 'Payment Method';
            Transaction::create([
                'user_id' => $withdrawal->user_id,
                'type' => 'withdrawal',
                'amount' => $withdrawal->amount,
                'description' => 'Withdrawal via ' . $paymentMethodName,
                'reference_id' => $withdrawal->id,
                'reference_type' => Withdrawal::class,
                'status' => 'completed',
            ]);

            // Send notification to user
            NotificationService::sendWithdrawalApproved($withdrawal);

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

            // Refund the amount back to earning wallets (mining_earning + referral_earning)
            // Use tracked breakdown if available, otherwise refund proportionally
            $user = $withdrawal->user;
            
            if ($withdrawal->deducted_from_mining !== null && $withdrawal->deducted_from_referral !== null) {
                // Use tracked breakdown for accurate refund
                $user->mining_earning += $withdrawal->deducted_from_mining;
                $user->referral_earning += $withdrawal->deducted_from_referral;
            } else {
                // Fallback for old withdrawals without tracking data
                // Refund proportionally based on current earning balances
                $totalEarning = ($user->mining_earning ?? 0) + ($user->referral_earning ?? 0);
                
                if ($totalEarning > 0) {
                    // Refund proportionally
                    $miningRatio = ($user->mining_earning ?? 0) / $totalEarning;
                    $referralRatio = ($user->referral_earning ?? 0) / $totalEarning;
                    
                    $refundToMining = $withdrawal->amount * $miningRatio;
                    $refundToReferral = $withdrawal->amount * $referralRatio;
                    
                    // Adjust for rounding
                    $totalRefunded = $refundToMining + $refundToReferral;
                    if (abs($totalRefunded - $withdrawal->amount) > 0.01) {
                        $difference = $withdrawal->amount - $totalRefunded;
                        $refundToMining += $difference;
                    }
                    
                    $user->mining_earning += $refundToMining;
                    $user->referral_earning += $refundToReferral;
                } else {
                    // If no earning balance exists, refund all to mining_earning
                    $user->mining_earning += $withdrawal->amount;
                }
            }
            
            $user->updateNetBalance();

            // Update withdrawal status
            $withdrawal->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'admin_notes' => $request->admin_notes,
            ]);

            // Send notification to user
            NotificationService::sendWithdrawalRejected($withdrawal);

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
