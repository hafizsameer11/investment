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
        Schema::create('pending_referral_commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('referrer_id');
            $table->unsignedBigInteger('investor_id');
            $table->unsignedBigInteger('investment_id');
            $table->unsignedBigInteger('mining_plan_id');
            $table->integer('level'); // 1-5
            $table->decimal('investment_amount', 15, 2);
            $table->decimal('commission_rate', 5, 2);
            $table->decimal('commission_amount', 15, 2);
            $table->boolean('is_claimed')->default(false);
            $table->timestamp('claimed_at')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('referrer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('investor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('investment_id')->references('id')->on('investments')->onDelete('cascade');
            $table->foreign('mining_plan_id')->references('id')->on('mining_plans')->onDelete('cascade');

            // Indexes for performance
            $table->index('referrer_id');
            $table->index('investor_id');
            $table->index('investment_id');
            $table->index('is_claimed');
            $table->index(['referrer_id', 'is_claimed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_referral_commissions');
    }
};
