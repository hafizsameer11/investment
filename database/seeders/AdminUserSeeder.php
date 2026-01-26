<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user already exists
        $adminExists = User::where('role', 'admin')->exists();

        if (!$adminExists) {
            $adminName = 'Admin';
            
            // Create admin user first (without referral code)
            $admin = User::create([
                'name' => $adminName,
                'email' => 'admin@gmail.com',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => null,
            ]);

            // Generate referral code using admin's name and ID
            $referCode = User::generateReferralCode($adminName, $admin->id);
            
            // Update admin with referral code
            $admin->update(['refer_code' => $referCode]);
            $admin->refresh();

            $this->command->info('Admin user created successfully!');
            $this->command->info('Email: admin@gmail.com');
            $this->command->info('Username: admin');
            $this->command->info('Password: admin123');
            $this->command->info('Referral Code: ' . $referCode);
            $this->command->warn('Please change the password after first login!');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }
}

