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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->integer('price'); // in Rupiah, e.g. 24000
            $table->integer('stock')->default(0);
            $table->string('unit')->default('gelas'); // gelas, porsi, pcs
            $table->string('image')->nullable(); // path to uploaded image
            $table->boolean('is_active')->default(true); // tampil di katalog kasir
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
