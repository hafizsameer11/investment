<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DepositPaymentMethod;
use Illuminate\Support\Facades\File;

class DepositPaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'account_type' => 'Easypaisa',
                'account_number' => '03001234567',
                'minimum_deposit' => 100.00,
                'maximum_deposit' => 50000.00,
                'image_name' => 'easypaisa.png',
                'is_active' => true,
            ],
            [
                'account_type' => 'Jazzcash',
                'account_number' => '03009876543',
                'minimum_deposit' => 100.00,
                'maximum_deposit' => 50000.00,
                'image_name' => 'jazzcash.png',
                'is_active' => true,
            ],
            [
                'account_type' => 'Bank Account',
                'account_number' => 'PK12ABCD1234567890123456',
                'minimum_deposit' => 500.00,
                'maximum_deposit' => 100000.00,
                'image_name' => 'bank.png',
                'is_active' => true,
            ],
            [
                'account_type' => 'Crypto Wallet',
                'account_number' => '0x1234567890abcdef1234567890abcdef12345678',
                'minimum_deposit' => 50.00,
                'maximum_deposit' => 200000.00,
                'image_name' => null, // No crypto image available
                'is_active' => true,
            ],
        ];

        // Create admin payment method directory if it doesn't exist
        $adminImageDir = public_path('assets/admin/images/payment-method');
        if (!File::exists($adminImageDir)) {
            File::makeDirectory($adminImageDir, 0755, true);
        }

        // Dashboard image directory
        $dashboardImageDir = public_path('assets/dashboard/images/payment-method');

        foreach ($paymentMethods as $method) {
            $imagePath = null;

            // Copy image from dashboard to admin directory if it exists
            if ($method['image_name']) {
                $sourceImage = $dashboardImageDir . '/' . $method['image_name'];
                $destinationImage = $adminImageDir . '/' . $method['image_name'];

                if (File::exists($sourceImage)) {
                    File::copy($sourceImage, $destinationImage);
                    $imagePath = 'assets/admin/images/payment-method/' . $method['image_name'];
                }
            }

            // Remove image_name from array before creating
            unset($method['image_name']);

            DepositPaymentMethod::updateOrCreate(
                [
                    'account_type' => $method['account_type'],
                    'account_number' => $method['account_number'],
                ],
                array_merge($method, ['image' => $imagePath])
            );
        }

        $this->command->info('Deposit payment methods seeded successfully!');
    }
}
