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
            $table->string('account_number')->nullable()->change();
            $table->string('till_id')->nullable()->after('account_number');
            $table->string('qr_scanner')->nullable()->after('till_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deposit_payment_methods', function (Blueprint $table) {
            $table->dropColumn(['till_id', 'qr_scanner']);
            $table->string('account_number')->nullable(false)->change();
        });
    }
};
