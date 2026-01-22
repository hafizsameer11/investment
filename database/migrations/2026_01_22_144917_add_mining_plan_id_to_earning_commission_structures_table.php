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
        Schema::table('earning_commission_structures', function (Blueprint $table) {
            // Remove unique constraint on level to allow multiple entries per level (one per plan)
            $table->dropUnique(['level']);

            $table->unsignedBigInteger('mining_plan_id')->nullable()->after('is_active');
            $table->foreign('mining_plan_id')->references('id')->on('mining_plans')->onDelete('cascade');
            // Add unique constraint on level and mining_plan_id combination
            $table->unique(['level', 'mining_plan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('earning_commission_structures', function (Blueprint $table) {
            $table->dropForeign(['mining_plan_id']);
            $table->dropUnique(['level', 'mining_plan_id']);
            $table->dropColumn('mining_plan_id');
            // Restore unique constraint on level
            $table->unique('level');
        });
    }
};
