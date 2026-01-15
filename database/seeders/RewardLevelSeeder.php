<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RewardLevel;

class RewardLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rewardLevels = [
            [
                'level' => 1,
                'level_name' => 'Team Builder',
                'icon_class' => 'fas fa-user-tie',
                'icon_color' => 'gold',
                'investment_required' => 10.00,
                'reward_amount' => 2.00,
                'is_premium' => false,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'level' => 2,
                'level_name' => 'Team Leader',
                'icon_class' => 'fas fa-user-graduate',
                'icon_color' => 'gold',
                'investment_required' => 40.00,
                'reward_amount' => 5.00,
                'is_premium' => false,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'level' => 3,
                'level_name' => 'Team Director',
                'icon_class' => 'fas fa-briefcase',
                'icon_color' => 'gold',
                'investment_required' => 120.00,
                'reward_amount' => 8.00,
                'is_premium' => false,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'level' => 4,
                'level_name' => 'Team Master',
                'icon_class' => 'fas fa-medal',
                'icon_color' => 'gold',
                'investment_required' => 200.00,
                'reward_amount' => 16.00,
                'is_premium' => false,
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'level' => 5,
                'level_name' => 'Team Chief',
                'icon_class' => 'fas fa-award',
                'icon_color' => 'silver',
                'investment_required' => 600.00,
                'reward_amount' => 50.00,
                'is_premium' => false,
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'level' => 6,
                'level_name' => 'Team Executive',
                'icon_class' => 'fas fa-gem',
                'icon_color' => 'purple',
                'investment_required' => 1000.00,
                'reward_amount' => 170.00,
                'is_premium' => false,
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'level' => 7,
                'level_name' => 'Team Captain',
                'icon_class' => 'fas fa-star',
                'icon_color' => 'red',
                'investment_required' => 2500.00,
                'reward_amount' => 500.00,
                'is_premium' => false,
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'level' => 8,
                'level_name' => 'Team Commander',
                'icon_class' => 'fas fa-chess-king',
                'icon_color' => 'red',
                'investment_required' => 8000.00,
                'reward_amount' => 2000.00,
                'is_premium' => false,
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'level' => 9,
                'level_name' => 'Team Head',
                'icon_class' => 'fas fa-chess-queen',
                'icon_color' => 'red',
                'investment_required' => 15000.00,
                'reward_amount' => 4500.00,
                'is_premium' => false,
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'level' => 10,
                'level_name' => 'Team President',
                'icon_class' => 'fas fa-crown',
                'icon_color' => 'red',
                'investment_required' => 25000.00,
                'reward_amount' => 8000.00,
                'is_premium' => true,
                'sort_order' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($rewardLevels as $rewardLevel) {
            RewardLevel::updateOrCreate(
                ['level' => $rewardLevel['level']],
                $rewardLevel
            );
        }
    }
}

