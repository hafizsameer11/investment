<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MiningPlan;

class MiningPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Lithium',
                'tagline' => 'Advanced Mining Plan for Maximum Returns',
                'subtitle' => 'Earn through lithium mining',
                'icon_class' => 'fas fa-gem',
                'min_investment' => 2.00,
                'max_investment' => 100000.00,
                'daily_roi_min' => 3.00,
                'daily_roi_max' => 4.00,
                'hourly_rate' => 0.00,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Platinum',
                'tagline' => 'Premium Mining Plan for High Returns',
                'subtitle' => 'Earn through platinum mining',
                'icon_class' => 'fas fa-gem',
                'min_investment' => 1000.00,
                'max_investment' => 50000.00,
                'daily_roi_min' => 4.00,
                'daily_roi_max' => 5.00,
                'hourly_rate' => 0.00,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Diamond',
                'tagline' => 'Elite Mining Plan for Maximum Profits',
                'subtitle' => 'Earn through diamond mining',
                'icon_class' => 'fas fa-crown',
                'min_investment' => 5000.00,
                'max_investment' => 200000.00,
                'daily_roi_min' => 5.00,
                'daily_roi_max' => 6.00,
                'hourly_rate' => 0.00,
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            MiningPlan::updateOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }
    }
}
