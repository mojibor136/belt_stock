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
        Schema::create('memo_item_sizes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('memo_item_id');
            $table->string('size')->default(0);
            $table->string('quantity')->default(0);
            $table->string('subtotal')->default(0);
            $table->timestamps();
            $table->index(['size', 'quantity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memo_item_sizes');
    }
};
