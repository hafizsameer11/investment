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
            $now = now();

            foreach ($investments as $investment) {
                try {
                    // Determine the starting point for profit calculation
                    // If last_profit_calculated_at is null, use the investment's created_at
                    $lastCalculatedAt = $investment->last_profit_calculated_at ?? $investment->created_at;
                    
                    // Calculate hours elapsed since last calculation (using seconds for accuracy)
                    $secondsElapsed = $now->diffInSeconds($lastCalculatedAt);
                    $hoursElapsed = $secondsElapsed / 3600;
                    
                    // Only calculate if at least 1 hour has passed (for scheduled command)
                    if ($hoursElapsed < 1) {
                        continue;
                    }

                    // Calculate hourly profit: amount * (hourly_rate / 100)
                    $hourlyRate = $investment->hourly_rate ?? 0;
                    
                    // Skip if hourly rate is not set or zero
                    if ($hourlyRate <= 0) {
                        $this->warn("Investment ID {$investment->id} has no hourly rate set. Skipping.");
                        continue;
                    }

                    $hourlyProfit = $investment->amount * ($hourlyRate / 100);
                    
                    // Calculate total profit for all hours elapsed
                    $totalProfitForPeriod = $hourlyProfit * $hoursElapsed;

                    if ($totalProfitForPeriod > 0) {
                        DB::beginTransaction();
                        
                        try {
                            // Add profit to user's mining_earning (total)
                            $user = $investment->user;
                            $user->mining_earning = ($user->mining_earning ?? 0) + $totalProfitForPeriod;
                            
                            // Add profit to investment's unclaimed_profit (per investment)
                            $investment->unclaimed_profit = ($investment->unclaimed_profit ?? 0) + $totalProfitForPeriod;
                            
                            // Update investment's total profit earned
                            $investment->total_profit_earned = ($investment->total_profit_earned ?? 0) + $totalProfitForPeriod;
                            $investment->last_profit_calculated_at = $now;
                            
                            // Update user's net balance
                            $user->updateNetBalance();
                            
                            // Save changes
                            $user->save();
                            $investment->save();

                            $totalProfit += $totalProfitForPeriod;
                            $processedCount++;
                            
                            $this->info("Investment ID {$investment->id}: Calculated $" . number_format($totalProfitForPeriod, 2) . " for " . number_format($hoursElapsed, 2) . " hours");
                            
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Log::error("Error saving profit for investment ID {$investment->id}: " . $e->getMessage());
                            $this->warn("Failed to save profit for investment ID {$investment->id}: " . $e->getMessage());
                        }
                    }
                } catch (\Exception $e) {
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
