<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_contributions', function (Blueprint $table) {
            $table->id('contri_id');
            $table->foreignId('member_id')->references('member_id')->on('members');
            $table->foreignId('transaction_id')->references('transaction_id')->on('transactions');
            $table->foreignId('fund_id')->references('fund_id')->on('funds');
            $table->enum('payment_status', ['paid', 'pending']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_contributions');
    }
};