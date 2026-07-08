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
    Schema::create('sales', function (Blueprint $table) {
        $table->id('id_sale');
        $table->string('invoice')->unique();
        $table->decimal('total_amount', 15, 2);
        $table->foreignId('id_user')->constrained('users', 'id_user'); // Siapa kasirnya
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
