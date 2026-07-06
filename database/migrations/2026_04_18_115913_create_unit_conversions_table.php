<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_conversions', function (Blueprint $table) {
            $table->id();
            // Relasi ke barang (Misal: Paku)
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            
            // Relasi ke satuan besarnya (Misal: Dus / Kotak / Sak)
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            
            // Nilai pengali ke Base Unit. Pakai desimal jaga-jaga kalau ada konversi pecahan (Misal 1.5)
            $table->decimal('multiplier', 10, 2); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_conversions');
    }
}; 