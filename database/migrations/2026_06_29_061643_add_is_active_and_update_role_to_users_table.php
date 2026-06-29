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
            $table->boolean('is_active')->default(true)->after('email_verified_at');
            $table->string('role')->default('member')->change(); // change enum to string to support member role
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_active');
            // Reverting string to enum is tricky in sqlite without full rebuild, but this is the standard fallback
            $table->enum('role', ['treasurer', 'kelian'])->change();
        });
    }
};
