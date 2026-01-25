<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DepositPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DepositPaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentMethods = DepositPaymentMethod::orderBy('created_at', 'desc')->get();
        return view('admin.pages.deposit-payment-method.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.deposit-payment-method.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
            'type' => 'required|in:rast,bank,crypto',
            'account_type' => 'required|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'bank_name' => 'required_if:type,bank|nullable|string|max:255',
            'account_number' => 'required|string|max:255',
            'minimum_deposit' => 'nullable|numeric|min:0',
            'maximum_deposit' => 'nullable|numeric|min:0',
            'minimum_withdrawal_amount' => 'nullable|numeric|min:0',
            'maximum_withdrawal_amount' => 'nullable|numeric|min:0',
        ]);

        // Additional validation: maximum should be greater than or equal to minimum
        if ($request->filled('minimum_deposit') && $request->filled('maximum_deposit')) {
            if ($request->maximum_deposit < $request->minimum_deposit) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['maximum_deposit' => 'Maximum deposit must be greater than or equal to minimum deposit.']);
            }
        }

        // Additional validation: maximum withdrawal should be greater than or equal to minimum withdrawal
        if ($request->filled('minimum_withdrawal_amount') && $request->filled('maximum_withdrawal_amount')) {
            if ($request->maximum_withdrawal_amount < $request->minimum_withdrawal_amount) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['maximum_withdrawal_amount' => 'Maximum withdrawal must be greater than or equal to minimum withdrawal.']);
            }
        }

        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();
                
                // Create directory if it doesn't exist
                $directory = public_path('assets/admin/images/payment-method');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                // Move the file using move() method
                if (!$image->move($directory, $imageName)) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['image' => 'Failed to upload image. Please try again.']);
                }
                
                $imagePath = 'assets/admin/images/payment-method/' . $imageName;
            }

            $paymentMethod = DepositPaymentMethod::create([
                'image' => $imagePath,
                'type' => $request->type,
                'account_type' => $request->account_type,
                'account_name' => $request->account_name ?? null,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'minimum_deposit' => $request->minimum_deposit ?? null,
                'maximum_deposit' => $request->maximum_deposit ?? null,
                'minimum_withdrawal_amount' => $request->minimum_withdrawal_amount ?? null,
                'maximum_withdrawal_amount' => $request->maximum_withdrawal_amount ?? null,
                'is_active' => $request->has('is_active') ? true : false,
                'allowed_for_deposit' => $request->has('allowed_for_deposit') ? true : false,
                'allowed_for_withdrawal' => $request->has('allowed_for_withdrawal') ? true : false,
            ]);

            return redirect()->route('admin.deposit-payment-method.index')
                ->with('success', 'Deposit payment method created successfully.');
        } catch (\Exception $e) {
            Log::error('Deposit Payment Method Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create payment method. Please check the logs for details.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $paymentMethod = DepositPaymentMethod::findOrFail($id);
        return view('admin.pages.deposit-payment-method.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $paymentMethod = DepositPaymentMethod::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image',
            'type' => 'required|in:rast,bank,crypto',
            'account_type' => 'required|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'bank_name' => 'required_if:type,bank|nullable|string|max:255',
            'account_number' => 'required|string|max:255',
            'minimum_deposit' => 'nullable|numeric|min:0',
            'maximum_deposit' => 'nullable|numeric|min:0',
            'minimum_withdrawal_amount' => 'nullable|numeric|min:0',
            'maximum_withdrawal_amount' => 'nullable|numeric|min:0',
        ]);

        // Additional validation: maximum should be greater than or equal to minimum
        if ($request->filled('minimum_deposit') && $request->filled('maximum_deposit')) {
            if ($request->maximum_deposit < $request->minimum_deposit) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['maximum_deposit' => 'Maximum deposit must be greater than or equal to minimum deposit.']);
            }
        }

        // Additional validation: maximum withdrawal should be greater than or equal to minimum withdrawal
        if ($request->filled('minimum_withdrawal_amount') && $request->filled('maximum_withdrawal_amount')) {
            if ($request->maximum_withdrawal_amount < $request->minimum_withdrawal_amount) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['maximum_withdrawal_amount' => 'Maximum withdrawal must be greater than or equal to minimum withdrawal.']);
            }
        }

        $imagePath = $paymentMethod->image;
        
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($paymentMethod->image && file_exists(public_path($paymentMethod->image))) {
                unlink(public_path($paymentMethod->image));
            }
            
            $image = $request->file('image');
            $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            $directory = public_path('assets/admin/images/payment-method');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Move the file using move() method
            $image->move($directory, $imageName);
            $imagePath = 'assets/admin/images/payment-method/' . $imageName;
        }

        $paymentMethod->update([
            'image' => $imagePath,
            'type' => $request->type,
            'account_type' => $request->account_type,
            'account_name' => $request->account_name ?? null,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'minimum_deposit' => $request->minimum_deposit ?? null,
            'maximum_deposit' => $request->maximum_deposit ?? null,
            'minimum_withdrawal_amount' => $request->minimum_withdrawal_amount ?? null,
            'maximum_withdrawal_amount' => $request->maximum_withdrawal_amount ?? null,
            'is_active' => $request->has('is_active') ? true : false,
            'allowed_for_deposit' => $request->has('allowed_for_deposit') ? true : false,
            'allowed_for_withdrawal' => $request->has('allowed_for_withdrawal') ? true : false,
        ]);

        return redirect()->route('admin.deposit-payment-method.index')
            ->with('warning', 'Deposit payment method updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $paymentMethod = DepositPaymentMethod::findOrFail($id);
        
        // Delete image if exists
        if ($paymentMethod->image && file_exists(public_path($paymentMethod->image))) {
            unlink(public_path($paymentMethod->image));
        }
        
        $paymentMethod->delete();

        return redirect()->route('admin.deposit-payment-method.index')
            ->with('danger', 'Deposit payment method deleted successfully.');
    }
}

