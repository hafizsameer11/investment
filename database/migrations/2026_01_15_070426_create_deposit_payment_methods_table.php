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
        Schema::create('deposit_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('account_type');
            $table->string('account_number');
            $table->boolean('is_active')->default(true);
            $table->decimal('minimum_deposit', 15, 2)->nullable();
            $table->decimal('maximum_deposit', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit_payment_methods');
    }
};
