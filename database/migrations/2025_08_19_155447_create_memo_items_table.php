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
        Schema::create('memo_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memo_id');
            $table->unsignedBigInteger('brand_id')->index();
            $table->unsignedBigInteger('group_id')->index();
            $table->string('inch_rate')->default(0);
            $table->string('piece_rate')->default(0);
            $table->string('cost_piece_rate')->default(0);
            $table->string('cost_inch_rate')->default(0);
            $table->string('item_total')->default(0);
            $table->timestamps();
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memo_items');
    }
};
