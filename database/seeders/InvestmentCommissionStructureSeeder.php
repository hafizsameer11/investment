<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InvestmentCommissionStructure;

class InvestmentCommissionStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commissions = [
            [
                'level' => 1,
                'level_name' => 'Direct Referral',
                'commission_rate' => 6.00,
                'is_active' => true,
            ],
            [
                'level' => 2,
                'level_name' => 'Second Level',
                'commission_rate' => 3.00,
                'is_active' => true,
            ],
            [
                'level' => 3,
                'level_name' => 'Third Level',
                'commission_rate' => 3.00,
                'is_active' => true,
            ],
            [
                'level' => 4,
                'level_name' => 'Fourth Level',
                'commission_rate' => 3.00,
                'is_active' => true,
            ],
            [
                'level' => 5,
                'level_name' => 'Fifth Level',
                'commission_rate' => 3.00,
                'is_active' => true,
            ],
        ];

        foreach ($commissions as $commission) {
            InvestmentCommissionStructure::updateOrCreate(
                ['level' => $commission['level']],
                $commission
            );
        }
    }
}
