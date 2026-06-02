<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('funds', function (Blueprint $table) {
            $table->id('fund_id');
            $table->foreignId('group_id')->references('group_id')->on('community_groups')->onDelete('cascade');
            $table->foreignId('activity_id')->references('activity_id')->on('activities')->onDelete('cascade');
            $table->string('name', 50);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->text('description');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funds');
    }
};