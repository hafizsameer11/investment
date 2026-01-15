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
            $referCode = User::generateReferralCode($adminName);

            User::create([
                'name' => $adminName,
                'email' => 'admin@gmail.com',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'refer_code' => $referCode,
                'phone' => null,
            ]);

            $this->command->info('Admin user created successfully!');
            $this->command->info('Email: admin@example.com');
            $this->command->info('Username: admin');
            $this->command->info('Password: password');
            $this->command->warn('Please change the password after first login!');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }
}

