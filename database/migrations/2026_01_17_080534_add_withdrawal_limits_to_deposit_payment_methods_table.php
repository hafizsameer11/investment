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
        Schema::table('deposit_payment_methods', function (Blueprint $table) {
            $table->decimal('minimum_withdrawal_amount', 15, 2)->nullable()->after('maximum_deposit');
            $table->decimal('maximum_withdrawal_amount', 15, 2)->nullable()->after('minimum_withdrawal_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deposit_payment_methods', function (Blueprint $table) {
            $table->dropColumn(['minimum_withdrawal_amount', 'maximum_withdrawal_amount']);
        });
    }
};
