<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('mining_plan_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->enum('source_balance', ['fund_wallet', 'earning_balance']);
            $table->decimal('hourly_rate', 5, 2);
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->timestamp('last_profit_calculated_at')->nullable();
            $table->decimal('total_profit_earned', 15, 2)->default(0);
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index('user_id');
            $table->index('mining_plan_id');
            $table->index('status');
            $table->index('last_profit_calculated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
