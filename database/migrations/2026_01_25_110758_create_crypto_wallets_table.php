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
        Schema::create('crypto_wallets', function (Blueprint $table) {
            $table->id();
            $table->enum('network', ['bnb_smart_chain', 'tron'])->unique();
            $table->string('network_display_name');
            $table->string('wallet_address');
            $table->string('qr_code_image')->nullable();
            $table->string('token')->default('USDT');
            $table->boolean('is_active')->default(true);
            $table->boolean('allowed_for_deposit')->default(true);
            $table->boolean('allowed_for_withdrawal')->default(true);
            $table->decimal('minimum_deposit', 15, 2)->nullable();
            $table->decimal('maximum_deposit', 15, 2)->nullable();
            $table->decimal('minimum_withdrawal', 15, 2)->nullable();
            $table->decimal('maximum_withdrawal', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_wallets');
    }
};
