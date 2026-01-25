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
            $table->string('type')->default('rast')->after('image');
            $table->string('bank_name')->nullable()->after('account_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deposit_payment_methods', function (Blueprint $table) {
            $table->dropColumn(['type', 'bank_name']);
        });
    }
};
