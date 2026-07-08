<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_ins', function (Blueprint $table) {
            $table->id('id_stock_in');
            $table->string('reference')->unique();
            $table->foreignId('id_supplier')->constrained('suppliers', 'id_supplier')->onDelete('cascade');
            $table->foreignId('id_product')->constrained('products', 'id_product')->onDelete('cascade');
            $table->foreignId('id_user')->constrained('users', 'id_user'); // Siapa admin yang input
            $table->decimal('qty', 10, 2);
            $table->date('date');
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_ins');
    }
};