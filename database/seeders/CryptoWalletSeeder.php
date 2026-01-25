<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CryptoWallet;

class CryptoWalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cryptoWallets = [
            [
                'network' => 'bnb_smart_chain',
                'network_display_name' => 'BNB Smart Chain (BEP20)',
                'wallet_address' => '0x0000000000000000000000000000000000000000', // Placeholder - admin should update
                'qr_code_image' => null, // Admin should upload
                'token' => 'USDT',
                'is_active' => true,
                'allowed_for_deposit' => true,
                'allowed_for_withdrawal' => true,
                'minimum_deposit' => 10.00,
                'maximum_deposit' => 50000.00,
                'minimum_withdrawal' => 10.00,
                'maximum_withdrawal' => 50000.00,
            ],
            [
                'network' => 'tron',
                'network_display_name' => 'Tron',
                'wallet_address' => 'T0000000000000000000000000000000000000000', // Placeholder - admin should update
                'qr_code_image' => null, // Admin should upload
                'token' => 'USDT',
                'is_active' => true,
                'allowed_for_deposit' => true,
                'allowed_for_withdrawal' => true,
                'minimum_deposit' => 10.00,
                'maximum_deposit' => 50000.00,
                'minimum_withdrawal' => 10.00,
                'maximum_withdrawal' => 50000.00,
            ],
        ];

        foreach ($cryptoWallets as $wallet) {
            CryptoWallet::updateOrCreate(
                ['network' => $wallet['network']],
                $wallet
            );
        }

        $this->command->info('Crypto wallets seeded successfully!');
    }
}
