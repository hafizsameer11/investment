<?php

namespace App\Console\Commands;

use App\Models\Chat;
use Illuminate\Console\Command;

class AutoCloseChats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chats:auto-close {--hours=2.5 : Hours of inactivity before closing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-close chats that have been inactive for a specified time (default 2.5 hours)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = (float) $this->option('hours');
        
        $this->info("Checking for chats inactive for {$hours} hours...");
        
        $closedCount = Chat::autoCloseOldChats($hours);
        
        if ($closedCount > 0) {
            $this->info("Successfully closed {$closedCount} chat(s).");
        } else {
            $this->info("No chats needed to be closed.");
        }
        
        return Command::SUCCESS;
    }
}
