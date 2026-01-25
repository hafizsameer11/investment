<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CryptoWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CryptoWalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cryptoWallets = CryptoWallet::orderBy('network', 'asc')->get();
        return view('admin.pages.crypto-wallet.index', compact('cryptoWallets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.crypto-wallet.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'network' => 'required|in:bnb_smart_chain,tron|unique:crypto_wallets,network',
            'network_display_name' => 'required|string|max:255',
            'wallet_address' => 'required|string|max:255',
            'qr_code_image' => 'nullable|image',
            'token' => 'required|string|max:10',
            'minimum_deposit' => 'nullable|numeric|min:0',
            'maximum_deposit' => 'nullable|numeric|min:0',
            'minimum_withdrawal' => 'nullable|numeric|min:0',
            'maximum_withdrawal' => 'nullable|numeric|min:0',
        ]);

        // Additional validation: maximum should be greater than or equal to minimum
        if ($request->filled('minimum_deposit') && $request->filled('maximum_deposit')) {
            if ($request->maximum_deposit < $request->minimum_deposit) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['maximum_deposit' => 'Maximum deposit must be greater than or equal to minimum deposit.']);
            }
        }

        if ($request->filled('minimum_withdrawal') && $request->filled('maximum_withdrawal')) {
            if ($request->maximum_withdrawal < $request->minimum_withdrawal) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['maximum_withdrawal' => 'Maximum withdrawal must be greater than or equal to minimum withdrawal.']);
            }
        }

        try {
            $qrCodePath = null;
            if ($request->hasFile('qr_code_image')) {
                $image = $request->file('qr_code_image');
                $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();
                
                // Create directory if it doesn't exist
                $directory = public_path('assets/admin/images/crypto-wallets');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                // Move the file
                if (!$image->move($directory, $imageName)) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['qr_code_image' => 'Failed to upload QR code image. Please try again.']);
                }
                
                $qrCodePath = 'assets/admin/images/crypto-wallets/' . $imageName;
            }

            $cryptoWallet = CryptoWallet::create([
                'network' => $request->network,
                'network_display_name' => $request->network_display_name,
                'wallet_address' => $request->wallet_address,
                'qr_code_image' => $qrCodePath,
                'token' => $request->token ?? 'USDT',
                'minimum_deposit' => $request->minimum_deposit ?? null,
                'maximum_deposit' => $request->maximum_deposit ?? null,
                'minimum_withdrawal' => $request->minimum_withdrawal ?? null,
                'maximum_withdrawal' => $request->maximum_withdrawal ?? null,
                'is_active' => $request->has('is_active') ? true : false,
                'allowed_for_deposit' => $request->has('allowed_for_deposit') ? true : false,
                'allowed_for_withdrawal' => $request->has('allowed_for_withdrawal') ? true : false,
            ]);

            return redirect()->route('admin.crypto-wallet.index')
                ->with('success', 'Crypto wallet created successfully.');
        } catch (\Exception $e) {
            Log::error('Crypto Wallet Store Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create crypto wallet. Please check the logs for details.']);
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
        $cryptoWallet = CryptoWallet::findOrFail($id);
        return view('admin.pages.crypto-wallet.edit', compact('cryptoWallet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cryptoWallet = CryptoWallet::findOrFail($id);

        $request->validate([
            'network' => 'required|in:bnb_smart_chain,tron|unique:crypto_wallets,network,' . $id,
            'network_display_name' => 'required|string|max:255',
            'wallet_address' => 'required|string|max:255',
            'qr_code_image' => 'nullable|image',
            'token' => 'required|string|max:10',
            'minimum_deposit' => 'nullable|numeric|min:0',
            'maximum_deposit' => 'nullable|numeric|min:0',
            'minimum_withdrawal' => 'nullable|numeric|min:0',
            'maximum_withdrawal' => 'nullable|numeric|min:0',
        ]);

        // Additional validation: maximum should be greater than or equal to minimum
        if ($request->filled('minimum_deposit') && $request->filled('maximum_deposit')) {
            if ($request->maximum_deposit < $request->minimum_deposit) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['maximum_deposit' => 'Maximum deposit must be greater than or equal to minimum deposit.']);
            }
        }

        if ($request->filled('minimum_withdrawal') && $request->filled('maximum_withdrawal')) {
            if ($request->maximum_withdrawal < $request->minimum_withdrawal) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['maximum_withdrawal' => 'Maximum withdrawal must be greater than or equal to minimum withdrawal.']);
            }
        }

        $qrCodePath = $cryptoWallet->qr_code_image;
        
        if ($request->hasFile('qr_code_image')) {
            // Delete old QR code image if exists
            if ($cryptoWallet->qr_code_image && file_exists(public_path($cryptoWallet->qr_code_image))) {
                unlink(public_path($cryptoWallet->qr_code_image));
            }
            
            $image = $request->file('qr_code_image');
            $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            $directory = public_path('assets/admin/images/crypto-wallets');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Move the file
            $image->move($directory, $imageName);
            $qrCodePath = 'assets/admin/images/crypto-wallets/' . $imageName;
        }

        $cryptoWallet->update([
            'network' => $request->network,
            'network_display_name' => $request->network_display_name,
            'wallet_address' => $request->wallet_address,
            'qr_code_image' => $qrCodePath,
            'token' => $request->token ?? 'USDT',
            'minimum_deposit' => $request->minimum_deposit ?? null,
            'maximum_deposit' => $request->maximum_deposit ?? null,
            'minimum_withdrawal' => $request->minimum_withdrawal ?? null,
            'maximum_withdrawal' => $request->maximum_withdrawal ?? null,
            'is_active' => $request->has('is_active') ? true : false,
            'allowed_for_deposit' => $request->has('allowed_for_deposit') ? true : false,
            'allowed_for_withdrawal' => $request->has('allowed_for_withdrawal') ? true : false,
        ]);

        return redirect()->route('admin.crypto-wallet.index')
            ->with('warning', 'Crypto wallet updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cryptoWallet = CryptoWallet::findOrFail($id);
        
        // Delete QR code image if exists
        if ($cryptoWallet->qr_code_image && file_exists(public_path($cryptoWallet->qr_code_image))) {
            unlink(public_path($cryptoWallet->qr_code_image));
        }
        
        $cryptoWallet->delete();

        return redirect()->route('admin.crypto-wallet.index')
            ->with('danger', 'Crypto wallet deleted successfully.');
    }
}
