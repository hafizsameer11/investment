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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type')->nullable(); // deposit, withdrawal, referral_earning, mining_earning, etc.
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable(); // For linking to deposits/withdrawals/reward levels
            $table->string('reference_type')->nullable(); // Polymorphic type: App\Models\Deposit, App\Models\Withdrawal, App\Models\RewardLevel, etc.
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('completed');
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index('user_id');
            $table->index('type');
            $table->index('status');
            $table->index('created_at');
            $table->index(['reference_id', 'reference_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

