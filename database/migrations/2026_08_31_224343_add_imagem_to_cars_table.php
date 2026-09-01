<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa a migração (adiciona a coluna).
     */
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            // Cria a coluna 'imagem' que aceita valor nulo e fica posicianda após 'preco'
            $table->string('imagem')->nullable()->after('preco');
        });
    }

    /**
     * Reverte a migração (remove a coluna).
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            // Apaga a coluna 'imagem' se precisares de fazer rollback
            $table->dropColumn('imagem');
        });
    }
};