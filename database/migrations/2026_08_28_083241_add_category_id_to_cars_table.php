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
    Schema::table('cars', function (Blueprint $table) {
        // Cria a coluna category_id conectada à tabela categories
        $table->foreignId('category_id')
              ->after('id')
              ->constrained('categories')
              ->onDelete('cascade'); 
    });
}

public function down(): void
{
    Schema::table('cars', function (Blueprint $table) {
        $table->dropForeign(['category_id']);
        $table->dropColumn('category_id');
    });
}
};
