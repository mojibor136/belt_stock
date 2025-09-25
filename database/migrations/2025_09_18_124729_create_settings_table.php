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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('fav_icon')->nullable();
            $table->string('site_logo')->nullable();
            $table->boolean('invoice')->default(0);
            $table->boolean('vendor_stock')->default(0);
            $table->boolean('auto_complete_memo')->default(0);
            $table->string('description')->nullable();
            $table->json('shop_name')->nullable();
            $table->json('shop_address')->nullable();
            $table->json('shop_phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
