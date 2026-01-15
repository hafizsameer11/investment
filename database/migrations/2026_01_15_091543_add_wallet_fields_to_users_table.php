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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('fund_wallet', 15, 2)->default(0)->after('referred_by');
            $table->decimal('mining_earning', 15, 2)->default(0)->after('fund_wallet');
            $table->decimal('referral_earning', 15, 2)->default(0)->after('mining_earning');
            $table->decimal('net_balance', 15, 2)->default(0)->after('referral_earning');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['fund_wallet', 'mining_earning', 'referral_earning', 'net_balance']);
        });
    }
};
