<?php

namespace App\Console\Commands;

use App\Models\Investment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CalculateMiningProfits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mining:calculate-profits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate hourly mining profits for all active investments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting mining profit calculation...');

        try {
            // Get all active investments
            $investments = Investment::where('status', 'active')
                ->with('user')
                ->get();

            if ($investments->isEmpty()) {
                $this->info('No active investments found.');
                return 0;
            }

            $processedCount = 0;
            $totalProfit = 0;

            foreach ($investments as $investment) {
                try {
                    DB::beginTransaction();

                    // Calculate profit: amount * (hourly_rate / 100)
                    $hourlyRate = $investment->hourly_rate ?? 0;
                    $profit = $investment->amount * ($hourlyRate / 100);

                    if ($profit > 0) {
                        // Add profit to user's mining_earning
                        $user = $investment->user;
                        $user->mining_earning = ($user->mining_earning ?? 0) + $profit;
                        
                        // Update investment's total profit earned
                        $investment->total_profit_earned = ($investment->total_profit_earned ?? 0) + $profit;
                        $investment->last_profit_calculated_at = now();
                        
                        // Update user's net balance
                        $user->updateNetBalance();
                        
                        // Save changes
                        $user->save();
                        $investment->save();

                        $totalProfit += $profit;
                        $processedCount++;
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("Error calculating profit for investment ID {$investment->id}: " . $e->getMessage());
                    $this->warn("Failed to process investment ID {$investment->id}: " . $e->getMessage());
                }
            }

            $this->info("Profit calculation completed!");
            $this->info("Processed: {$processedCount} investments");
            $this->info("Total profit distributed: $" . number_format($totalProfit, 2));

            return 0;
        } catch (\Exception $e) {
            Log::error('Error in mining profit calculation command: ' . $e->getMessage());
            $this->error('An error occurred: ' . $e->getMessage());
            return 1;
        }
    }
}
