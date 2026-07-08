<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique(); // Cth: SOP-20260418-001
            $table->foreignId('id_product')->constrained('products', 'id_product')->onDelete('cascade');
            $table->foreignId('id_user')->constrained('users', 'id_user'); // Staff Gudang
            $table->date('date');
             $table->decimal('qty', 10, 2);
            $table->integer('actual_qty'); // Stok fisik real
            $table->integer('difference'); // Selisih (+ atau -)
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_opnames');
    }
};