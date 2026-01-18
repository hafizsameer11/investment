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
        Schema::create('user_reward_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('reward_level_id')->constrained()->onDelete('cascade');
            $table->timestamp('achieved_at');
            $table->decimal('reward_amount_credited', 15, 2);
            $table->timestamps();
            
            // Unique constraint to prevent duplicate level completions
            $table->unique(['user_id', 'reward_level_id']);
            
            // Indexes for better query performance
            $table->index('user_id');
            $table->index('reward_level_id');
            $table->index('achieved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_reward_levels');
    }
};

