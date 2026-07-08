<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_outs', function (Blueprint $table) {
            $table->id('id_stock_out');
            $table->string('reference')->unique(); // Contoh: TRK-20260418-001
            $table->foreignId('id_product')->constrained('products', 'id_product')->onDelete('cascade');
            $table->foreignId('id_user')->constrained('users', 'id_user'); // Staff yang mengeluarkan barang
             $table->decimal('qty', 10, 2);
            $table->date('date');
            $table->string('reason'); // Alasan: Rusak, Retur, Dipakai Sendiri, dll
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_outs');
    }
};
