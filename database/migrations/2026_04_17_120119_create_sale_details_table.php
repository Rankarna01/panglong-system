<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id('id_sale_detail');
            $table->foreignId('id_sale')->constrained('sales', 'id_sale')->onDelete('cascade');
            $table->foreignId('id_product')->constrained('products', 'id_product'); // Barang yang dibeli
            $table->decimal('qty', 10, 2);
            $table->decimal('price', 15, 2); // Harga saat dibeli (jaga-jaga jika harga master berubah)
            $table->decimal('subtotal', 15, 2); // qty * price
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_details');
    }
};
