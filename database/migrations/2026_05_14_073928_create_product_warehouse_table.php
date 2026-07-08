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
        Schema::create('product_warehouse', function (Blueprint $table) {
            $table->id('id_product_warehouse');
            $table->foreignId('id_product')->constrained('products', 'id_product')->onDelete('cascade');
            $table->foreignId('id_warehouse')->constrained('warehouses', 'id_warehouse')->onDelete('cascade');
            $table->decimal('stock', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_warehouse');
    }
};
