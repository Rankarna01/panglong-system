<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_rack', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('rack_id')->constrained('racks')->onDelete('cascade');
            
            // Menggunakan decimal agar mendukung stok pecahan seperti 0.5 Kg
            $table->decimal('stock', 10, 2)->default(0); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_rack');
    }
};