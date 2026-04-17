<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Menambahkan kolom category_id setelah kolom name
            $table->foreignId('category_id')->after('name')->constrained('categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Untuk rollback jika diperlukan
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};