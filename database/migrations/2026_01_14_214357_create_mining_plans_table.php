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
        Schema::create('mining_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('tagline')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('icon_class')->nullable();
            $table->decimal('min_investment', 15, 2)->nullable();
            $table->decimal('max_investment', 15, 2)->nullable();
            $table->decimal('daily_roi_min', 5, 2)->nullable();
            $table->decimal('daily_roi_max', 5, 2)->nullable();
            $table->decimal('hourly_rate', 5, 2)->default(0);
            // $table->text('principal_return_policy')->nullable();
            // $table->text('principal_return_description')->nullable();
            // $table->text('description')->nullable();
            // $table->text('benefits')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mining_plans');
    }
};
