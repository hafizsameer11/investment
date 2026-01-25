<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DepositPaymentMethod;

class FixCryptoPaymentMethod extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:crypto-payment-method';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix existing crypto payment method to have correct type and flags';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing crypto payment methods...');

        // Find all payment methods with "Crypto" in the name or account_type
        $cryptoMethods = DepositPaymentMethod::where('account_type', 'like', '%Crypto%')
            ->orWhere('account_type', 'like', '%crypto%')
            ->get();

        if ($cryptoMethods->isEmpty()) {
            $this->warn('No crypto payment methods found. Creating one...');
            
            DepositPaymentMethod::create([
                'account_type' => 'Crypto Wallet',
                'account_number' => '0x0000000000000000000000000000000000000000',
                'type' => 'crypto',
                'minimum_deposit' => 50.00,
                'maximum_deposit' => 200000.00,
                'minimum_withdrawal_amount' => 50.00,
                'maximum_withdrawal_amount' => 200000.00,
                'is_active' => true,
                'allowed_for_deposit' => true,
                'allowed_for_withdrawal' => true,
            ]);
            
            $this->info('Crypto payment method created successfully!');
        } else {
            foreach ($cryptoMethods as $method) {
                $method->update([
                    'type' => 'crypto',
                    'allowed_for_deposit' => true,
                    'allowed_for_withdrawal' => true,
                    'is_active' => true,
                ]);
                
                $this->info("Updated payment method: {$method->account_type} (ID: {$method->id})");
            }
        }

        $this->info('Done! Crypto payment method should now appear in deposit and withdrawal pages.');
        
        return 0;
    }
}
