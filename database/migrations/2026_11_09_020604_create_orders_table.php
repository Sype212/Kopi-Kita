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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // e.g. KK-024
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // kasir
            $table->string('order_type')->default('dine_in'); // dine_in, takeaway
            $table->string('table_number')->nullable(); // Meja 04, null jika bawa pulang
            $table->string('customer_name')->nullable()->default('Pelanggan umum');
            $table->integer('subtotal'); // sebelum pajak
            $table->integer('tax'); // pajak 10%
            $table->integer('total'); // subtotal + tax
            $table->string('payment_method'); // tunai, qris, debit
            $table->integer('cash_received')->nullable(); // uang diterima (khusus tunai)
            $table->integer('cash_change')->nullable(); // kembalian
            $table->string('status')->default('diproses'); // diproses, siap_diambil, selesai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
