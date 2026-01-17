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
            $table->boolean('allowed_for_deposit')->default(true)->after('is_active');
            $table->boolean('allowed_for_withdrawal')->default(false)->after('allowed_for_deposit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deposit_payment_methods', function (Blueprint $table) {
            $table->dropColumn(['allowed_for_deposit', 'allowed_for_withdrawal']);
        });
    }
};
