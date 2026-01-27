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
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->decimal('deducted_from_mining', 15, 2)->nullable()->after('amount');
            $table->decimal('deducted_from_referral', 15, 2)->nullable()->after('deducted_from_mining');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->dropColumn(['deducted_from_mining', 'deducted_from_referral']);
        });
    }
};
