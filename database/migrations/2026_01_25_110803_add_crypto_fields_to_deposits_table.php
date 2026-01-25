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
        Schema::table('deposits', function (Blueprint $table) {
            $table->foreignId('crypto_wallet_id')->nullable()->after('deposit_payment_method_id')->constrained('crypto_wallets')->onDelete('set null');
            $table->string('user_wallet_address')->nullable()->after('account_holder_name');
            $table->string('crypto_network')->nullable()->after('user_wallet_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropForeign(['crypto_wallet_id']);
            $table->dropColumn(['crypto_wallet_id', 'user_wallet_address', 'crypto_network']);
        });
    }
};
