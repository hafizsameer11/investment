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
        Schema::table('investments', function (Blueprint $table) {
            $table->decimal('unclaimed_profit', 15, 2)->default(0)->after('total_profit_earned');
            $table->timestamp('last_claimed_at')->nullable()->after('unclaimed_profit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investments', function (Blueprint $table) {
            $table->dropColumn(['unclaimed_profit', 'last_claimed_at']);
        });
    }
};
