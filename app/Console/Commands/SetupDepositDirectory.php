<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupDepositDirectory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:deposit-directory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create and set up the deposit payment proofs directory with proper permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $directory = public_path('assets/deposits/payment-proofs');
        
        $this->info('Setting up deposit payment proofs directory...');
        
        // Create directory if it doesn't exist
        if (!File::exists($directory)) {
            if (File::makeDirectory($directory, 0755, true)) {
                $this->info("✓ Directory created: {$directory}");
            } else {
                $this->error("✗ Failed to create directory: {$directory}");
                $this->warn('You may need to create it manually with proper permissions.');
                return 1;
            }
        } else {
            $this->info("✓ Directory already exists: {$directory}");
        }
        
        // Check if directory is writable
        if (is_writable($directory)) {
            $this->info("✓ Directory is writable");
        } else {
            $this->warn("⚠ Directory exists but is not writable");
            $this->warn("Please run: chmod -R 755 {$directory}");
            $this->warn("And set ownership to your web server user");
            return 1;
        }
        
        $this->info('✓ Setup complete!');
        return 0;
    }
}


