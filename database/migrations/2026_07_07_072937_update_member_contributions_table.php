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
        Schema::table('member_contributions', function (Blueprint $table) {
            $table->string('period', 7)->after('fund_id')->default(date('Y-m')); // Format: YYYY-MM
            
            // Allow transaction_id to be null
            // For SQLite, changing foreign keys might have issues but we'll try standard change first
            $table->foreignId('transaction_id')->nullable()->change();
            
            // Change enum to string for wider support
            $table->string('payment_status')->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('member_contributions', function (Blueprint $table) {
            $table->dropColumn('period');
            $table->foreignId('transaction_id')->nullable(false)->change();
            // Restoring the enum is tricky so we leave it as string in rollback usually
        });
    }
};
