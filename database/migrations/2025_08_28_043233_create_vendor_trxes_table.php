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
        Schema::create('vendor_trxes', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('vendor_id')->index();
            $table->string('invoice_type')->nullable();
            $table->string('payment')->default(0);
            $table->string('invoice')->default(0);
            $table->string('debit_credit')->default(0);
            $table->enum('invoice_status', ['invoice', 'payment']);
            $table->enum('status' , ['credit', 'debit']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_trxes');
    }
};
