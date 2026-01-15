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
        Schema::create('reward_levels', function (Blueprint $table) {
            $table->id();
            $table->integer('level')->unique()->nullable();
            $table->string('level_name')->nullable();
            $table->string('icon_class')->nullable();
            $table->string('icon_color')->nullable();
            $table->decimal('investment_required', 15, 2)->nullable();
            $table->decimal('reward_amount', 15, 2)->nullable();
            $table->boolean('is_premium')->default(false);
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
        Schema::dropIfExists('reward_levels');
    }
};
