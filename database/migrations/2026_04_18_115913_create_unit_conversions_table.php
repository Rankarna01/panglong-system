<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_conversions', function (Blueprint $table) {
            $table->id('id_unit_conversion');
            $table->foreignId('id_product')->constrained('products', 'id_product')->onDelete('cascade');
            
            // Relasi ke satuan besarnya (Misal: Dus / Kotak / Sak)
            $table->foreignId('id_unit')->constrained('units', 'id_unit')->onDelete('cascade');
            
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